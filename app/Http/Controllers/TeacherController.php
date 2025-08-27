<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Exibe a lista de professores
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Teacher::with('school');

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->whereJsonContains('schools', $user->coordinator->school_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            // Coordenadores só podem filtrar pela sua escola
            if ($user->isCoordinator()) {
                $query->whereJsonContains('schools', $user->coordinator->school_id);
            } else {
                $query->whereJsonContains('schools', $request->school_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('specialty')) {
            $query->whereJsonContains('specialties', $request->specialty);
        }

        $teachers = $query->orderBy('name')->paginate(15);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        // Estatísticas - coordenadores só veem dados da sua escola
        if ($user->isCoordinator()) {
            $stats = [
                'total' => Teacher::whereJsonContains('schools', $user->coordinator->school_id)->count(),
                'active' => Teacher::whereJsonContains('schools', $user->coordinator->school_id)->where('status', 'ativo')->count(),
                'inactive' => Teacher::whereJsonContains('schools', $user->coordinator->school_id)->where('status', 'inativo')->count(),
                'retired' => Teacher::whereJsonContains('schools', $user->coordinator->school_id)->where('status', 'aposentado')->count(),
            ];
        } else {
            $stats = [
                'total' => Teacher::count(),
                'active' => Teacher::where('status', 'ativo')->count(),
                'inactive' => Teacher::where('status', 'inativo')->count(),
                'retired' => Teacher::where('status', 'aposentado')->count(),
            ];
        }

        return view('teachers.index', compact('teachers', 'schools', 'stats'));
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        $schools = School::active()->orderBy('name')->get();
        $specialties = $this->getSpecialties();

        return view('teachers.create', compact('schools', 'specialties'));
    }

    /**
     * Armazena um novo professor
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14|unique:teachers,cpf',
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'registration' => 'required|string|max:50|unique:teachers,registration',
            'specialty' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:ativo,inativo,aposentado,licenca',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'hiring_date' => 'nullable|date|before_or_equal:today',
            'contract_type' => 'nullable|string|max:50',
            'salary' => 'nullable|numeric|min:0',
            'workload' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'schools' => 'nullable|array',
            'schools.*' => 'exists:schools,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Upload da foto
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers/photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Criar professor
        $teacher = Teacher::create($data);

        // Log da atividade
        ActivityLog::log('create', 'Teacher', "Professor {$teacher->name} foi cadastrado", $teacher->id);

        return redirect()->route('teachers.index')
            ->with('success', 'Professor cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes do professor
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('notes');

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Exibe o formulário de edição
     */
    public function edit(Teacher $teacher)
    {
        $schools = School::active()->orderBy('name')->get();
        $specialties = $this->getSpecialties();

        return view('teachers.edit', compact('teacher', 'schools', 'specialties'));
    }

    /**
     * Atualiza o professor
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14|unique:teachers,cpf,' . $teacher->id,
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'registration' => 'required|string|max:50|unique:teachers,registration,' . $teacher->id,
            'specialty' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:ativo,inativo,aposentado,licenca',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'hiring_date' => 'nullable|date|before_or_equal:today',
            'contract_type' => 'nullable|string|max:50',
            'salary' => 'nullable|numeric|min:0',
            'workload' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'schools' => 'nullable|array',
            'schools.*' => 'exists:schools,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Upload da nova foto
        if ($request->hasFile('photo')) {
            // Remover foto antiga
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $photoPath = $request->file('photo')->store('teachers/photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Atualizar professor
        $teacher->update($data);

        // Log da atividade
        ActivityLog::log('update', 'Teacher', "Professor {$teacher->name} foi atualizado", $teacher->id);

        return redirect()->route('teachers.index')
            ->with('success', 'Professor atualizado com sucesso!');
    }

    /**
     * Remove o professor
     */
    public function destroy(Teacher $teacher)
    {
        // Verificar se pode ser removido
        if ($teacher->notes()->count() > 0) {
            return redirect()->route('teachers.index')
                ->with('error', 'Não é possível remover um professor que possui notas associadas.');
        }

        // Remover foto
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacherName = $teacher->name;
        $teacher->delete();

        // Log da atividade
        ActivityLog::log('delete', 'Teacher', "Professor {$teacherName} foi removido");

        return redirect()->route('teachers.index')
            ->with('success', 'Professor removido com sucesso!');
    }

    /**
     * Altera o status do professor
     */
    public function toggleStatus(Teacher $teacher)
    {
        $oldStatus = $teacher->status;
        $newStatus = $teacher->status === 'ativo' ? 'inativo' : 'ativo';

        $teacher->update(['status' => $newStatus]);

        // Log da atividade
        ActivityLog::log('update', 'Teacher', "Status do professor {$teacher->name} alterado de {$oldStatus} para {$newStatus}", $teacher->id);

        return redirect()->route('teachers.index')
            ->with('success', "Status do professor alterado para {$newStatus}!");
    }

    /**
     * API para buscar professores
     */
    public function apiIndex(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('registration', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        $teachers = $query->select('id', 'name', 'email', 'specialty', 'status')
                         ->orderBy('name')
                         ->limit(20)
                         ->get();

        return response()->json($teachers);
    }

    /**
     * Retorna lista de especialidades
     */
    private function getSpecialties()
    {
        return [
            'Matemática',
            'Português',
            'História',
            'Geografia',
            'Ciências',
            'Física',
            'Química',
            'Biologia',
            'Educação Física',
            'Arte',
            'Inglês',
            'Espanhol',
            'Filosofia',
            'Sociologia',
            'Literatura',
            'Redação',
            'Informática',
            'Música',
            'Teatro',
            'Dança',
            'Outras',
        ];
    }
}
