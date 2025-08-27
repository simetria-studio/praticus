<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\School;
use App\Models\Teacher;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SchoolClass::with(['school', 'coordinator']);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('school_id', $user->coordinator->school_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            // Coordenadores só podem filtrar pela sua escola
            if ($user->isCoordinator()) {
                $query->where('school_id', $user->coordinator->school_id);
            } else {
                $query->where('school_id', $request->school_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $classes = $query->orderBy('year', 'desc')->orderBy('name')->paginate(15);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        return view('classes.index', compact('classes', 'schools'));
    }

    public function create()
    {
        $user = Auth::user();

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        // Coordenadores só veem professores da sua escola
        $teachers = $user->isCoordinator()
            ? Teacher::active()->whereJsonContains('schools', $user->coordinator->school_id)->orderBy('name')->get()
            : Teacher::active()->orderBy('name')->get();

        return view('classes.create', compact('schools', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code',
            'grade' => 'required|string|max:50',
            'period' => 'required|in:manha,tarde,noite',
            'year' => 'required|integer|min:2020|max:2035',
            'capacity' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:ativa,inativa',
            'description' => 'nullable|string|max:1000',
            'school_id' => 'required|exists:schools,id',
            'coordinator_id' => 'nullable|exists:teachers,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $class = SchoolClass::create($validated);

        ActivityLog::log('create', 'Class', "Turma {$class->name} criada", $class->id);

        return redirect()->route('classes.index')->with('success', 'Turma criada com sucesso!');
    }

    public function show(SchoolClass $class)
    {
        $class->load(['school', 'coordinator', 'students']);
        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        $schools = School::active()->orderBy('name')->get();
        $teachers = Teacher::active()->orderBy('name')->get();
        return view('classes.edit', compact('class', 'schools', 'teachers'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code,' . $class->id,
            'grade' => 'required|string|max:50',
            'period' => 'required|in:manha,tarde,noite',
            'year' => 'required|integer|min:2020|max:2035',
            'capacity' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:ativa,inativa',
            'description' => 'nullable|string|max:1000',
            'school_id' => 'required|exists:schools,id',
            'coordinator_id' => 'nullable|exists:teachers,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $class->update($validated);

        ActivityLog::log('update', 'Class', "Turma {$class->name} atualizada", $class->id);

        return redirect()->route('classes.index')->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')->with('error', 'Não é possível excluir turma com alunos associados.');
        }

        $name = $class->name;
        $class->delete();

        ActivityLog::log('delete', 'Class', "Turma {$name} excluída");

        return redirect()->route('classes.index')->with('success', 'Turma excluída com sucesso!');
    }

    /**
     * Get teachers by school (for AJAX)
     */
    public function getTeachersBySchool(Request $request, School $school)
    {
        $teachers = Teacher::active()
            ->whereJsonContains('schools', $school->id)
            ->orderBy('name')
            ->get(['id', 'name', 'specialty', 'registration']);

        return response()->json($teachers);
    }
}
