<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\School;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CoordinatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Coordinator::with('school');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->bySchool($request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('specialty')) {
            $query->bySpecialty($request->specialty);
        }

        $coordinators = $query->orderBy('name')->paginate(15);
        $schools = School::active()->orderBy('name')->get();

        return view('coordinators.index', compact('coordinators', 'schools'));
    }

    public function create()
    {
        $schools = School::active()->orderBy('name')->get();
        return view('coordinators.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:coordinators,email',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14|unique:coordinators,cpf',
            'registration' => 'required|string|max:50|unique:coordinators,registration',
            'specialty' => 'nullable|string|max:100',
            'degree' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:ativo,inativo',
            'school_id' => 'required|exists:schools,id',
            'coordinated_grades' => 'nullable|array',
            'coordinated_grades.*' => 'string|max:50',
            'coordinated_subjects' => 'nullable|array',
            'coordinated_subjects.*' => 'string|max:100',
            'hiring_date' => 'nullable|date',
            'contract_type' => 'nullable|string|max:50',
            'salary' => 'nullable|numeric|min:0',
            'workload' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'observations' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload da foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('coordinators/photos', 'public');
            }

            $coordinator = Coordinator::create($validated);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'Coordinator',
                "Coordenador '{$coordinator->name}' foi cadastrado",
                $coordinator->id
            );

            DB::commit();

            return redirect()->route('coordinators.index')
                ->with('success', 'Coordenador cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar coordenador: ' . $e->getMessage());
        }
    }

    public function show(Coordinator $coordinator)
    {
        $coordinator->load(['school', 'classes']);
        return view('coordinators.show', compact('coordinator'));
    }

    public function edit(Coordinator $coordinator)
    {
        $schools = School::active()->orderBy('name')->get();
        return view('coordinators.edit', compact('coordinator', 'schools'));
    }

    public function update(Request $request, Coordinator $coordinator)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('coordinators', 'email')->ignore($coordinator->id)],
            'phone' => 'nullable|string|max:20',
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique('coordinators', 'cpf')->ignore($coordinator->id)],
            'registration' => ['required', 'string', 'max:50', Rule::unique('coordinators', 'registration')->ignore($coordinator->id)],
            'specialty' => 'nullable|string|max:100',
            'degree' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:ativo,inativo',
            'school_id' => 'required|exists:schools,id',
            'coordinated_grades' => 'nullable|array',
            'coordinated_grades.*' => 'string|max:50',
            'coordinated_subjects' => 'nullable|array',
            'coordinated_subjects.*' => 'string|max:100',
            'hiring_date' => 'nullable|date',
            'contract_type' => 'nullable|string|max:50',
            'salary' => 'nullable|numeric|min:0',
            'workload' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'observations' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto = $coordinator->photo;

            // Upload da nova foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('coordinators/photos', 'public');

                // Remove foto antiga
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            $coordinator->update($validated);

            // Registrar atividade
            ActivityLog::log(
                'update',
                'Coordinator',
                "Coordenador '{$coordinator->name}' foi atualizado",
                $coordinator->id
            );

            DB::commit();

            return redirect()->route('coordinators.index')
                ->with('success', 'Coordenador atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove nova foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar coordenador: ' . $e->getMessage());
        }
    }

    public function destroy(Coordinator $coordinator)
    {
        try {
            DB::beginTransaction();

            // Verificar se o coordenador está associado a turmas
            if ($coordinator->classes()->count() > 0) {
                return redirect()->route('coordinators.index')
                    ->with('error', 'Não é possível excluir coordenador com turmas associadas.');
            }

            // Registrar atividade antes de excluir
            ActivityLog::log(
                'delete',
                'Coordinator',
                "Coordenador '{$coordinator->name}' foi excluído",
                $coordinator->id
            );

            // Remove foto se existir
            if ($coordinator->photo && Storage::disk('public')->exists($coordinator->photo)) {
                Storage::disk('public')->delete($coordinator->photo);
            }

            $coordinator->delete();

            DB::commit();

            return redirect()->route('coordinators.index')
                ->with('success', 'Coordenador excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir coordenador: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Coordinator $coordinator)
    {
        try {
            $newStatus = $coordinator->status === 'ativo' ? 'inativo' : 'ativo';
            $coordinator->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Status do coordenador alterado para {$newStatus}!",
                'status' => $newStatus,
                'status_label' => $coordinator->status_label,
                'badge_class' => $coordinator->status_badge_class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do coordenador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBySchool(Request $request, School $school)
    {
        $coordinators = $school->coordinators()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'specialty', 'registration']);

        return response()->json($coordinators);
    }

    public function enableAccess(Coordinator $coordinator)
    {
        // Verifica se já existe usuário
        $existing = \App\Models\User::where('coordinator_id', $coordinator->id)->first();
        if ($existing) {
            return redirect()->route('coordinators.show', $coordinator)
                ->with('info', 'Acesso já estava liberado.');
        }

        try {
            \DB::beginTransaction();

            $passwordPlain = \Str::password(10);

            $user = \App\Models\User::create([
                'name' => $coordinator->name,
                'email' => $coordinator->email,
                'password' => bcrypt($passwordPlain),
                'role' => 'coordinator',
                'status' => 'ativo',
                'coordinator_id' => $coordinator->id,
                'is_coordinator' => true,
                'permissions' => [
                    'view_students' => true,
                    'create_students' => true,
                    'edit_students' => true,
                    'delete_students' => false,
                    'view_classes' => true,
                    'create_classes' => true,
                    'edit_classes' => true,
                    'delete_classes' => false,
                    'view_teachers' => true,
                    'create_teachers' => false,
                    'edit_teachers' => false,
                    'delete_teachers' => false,
                    'view_certificates' => true,
                    'create_certificates' => true,
                    'edit_certificates' => true,
                    'delete_certificates' => false,
                    'view_notes' => true,
                    'create_notes' => true,
                    'edit_notes' => true,
                    'delete_notes' => false,
                    'view_reports' => true,
                    'export_data' => true,
                ],
            ]);

            ActivityLog::log('create', 'User', "Usuário coordenador criado para {$coordinator->name}", $user->id);

            \DB::commit();

            // Exibir senha gerada uma vez (não armazenar)
            return redirect()->route('coordinators.show', $coordinator)
                ->with('success', 'Acesso liberado com sucesso!')
                ->with('generated_password', $passwordPlain);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('coordinators.show', $coordinator)
                ->with('error', 'Falha ao liberar acesso: ' . $e->getMessage());
        }
    }
}
