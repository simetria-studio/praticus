<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Student::with(['school', 'schoolClass']);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('school_id', $user->coordinator->school_id);
        }

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('enrollment', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            // Coordenadores só podem filtrar pela sua escola
            if ($user->isCoordinator()) {
                $query->where('school_id', $user->coordinator->school_id);
            } else {
                $query->bySchool($request->school_id);
            }
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('grade')) {
            $query->byGrade($request->grade);
        }

        if ($request->filled('school_year')) {
            $query->bySchoolYear($request->school_year);
        }

        $students = $query->orderBy('name')->paginate(10);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        return view('students.index', compact('students', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        // Coordenadores só veem turmas da sua escola
        $classes = $user->isCoordinator()
            ? $user->coordinator->school->classes()->active()->orderBy('year', 'desc')->orderBy('name')->get()
            : SchoolClass::active()->orderBy('year', 'desc')->orderBy('name')->get();

        return view('students.create', compact('schools', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14|unique:students,cpf',
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enrollment' => 'required|string|max:50|unique:students,enrollment',
            'school_id' => 'required|exists:schools,id',
            'grade' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'school_year' => 'nullable|integer|min:2020|max:2030',
            'status' => 'required|in:ativo,inativo,transferido,formado,evadido',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_cpf' => 'nullable|string|max:14',
            'guardian_relationship' => 'nullable|in:pai,mae,avo,ava,tio,tia,responsavel_legal,outro',
            'medical_info' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // Upload da foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('students', 'public');
            }

            $student = Student::create($validated);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'Student',
                "Aluno '{$student->name}' foi cadastrado",
                $student->id
            );

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar aluno: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('school');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $schools = School::active()->orderBy('name')->get();
        $classes = SchoolClass::active()->orderBy('year', 'desc')->orderBy('name')->get();
        $student->load('school');
        return view('students.edit', compact('student', 'schools', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('students', 'email')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique('students', 'cpf')->ignore($student->id)],
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enrollment' => ['required', 'string', 'max:50', Rule::unique('students', 'enrollment')->ignore($student->id)],
            'school_id' => 'required|exists:schools,id',
            'grade' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'school_year' => 'nullable|integer|min:2020|max:2030',
            'status' => 'required|in:ativo,inativo,transferido,formado,evadido',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_cpf' => 'nullable|string|max:14',
            'guardian_relationship' => 'nullable|in:pai,mae,avo,ava,tio,tia,responsavel_legal,outro',
            'medical_info' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto = $student->photo;

            // Upload da nova foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('students', 'public');

                // Remove foto antiga
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            $student->update($validated);

            // Registrar atividade
            ActivityLog::log(
                'update',
                'Student',
                "Aluno '{$student->name}' foi atualizado",
                $student->id
            );

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove nova foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar aluno: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Registrar atividade antes de excluir
            ActivityLog::log(
                'delete',
                'Student',
                "Aluno '{$student->name}' foi excluído",
                $student->id
            );

            // Remove foto se existir
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $student->delete();

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir aluno: ' . $e->getMessage());
        }
    }

    /**
     * Toggle student status
     */
    public function toggleStatus(Student $student)
    {
        try {
            $newStatus = $student->status === 'ativo' ? 'inativo' : 'ativo';
            $student->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Status do aluno alterado para {$newStatus}!",
                'status' => $newStatus,
                'status_label' => $student->status_label,
                'badge_class' => $student->status_badge_class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do aluno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students by school (for AJAX)
     */
    public function getBySchool(Request $request, School $school)
    {
        $students = $school->students()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'enrollment']);

        return response()->json($students);
    }

    /**
     * Get classes by school (for AJAX)
     */
    public function getClassesBySchool(Request $request, School $school)
    {
        $classes = $school->classes()
            ->active()
            ->orderBy('year', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'year', 'grade']);

        return response()->json($classes);
    }
}
