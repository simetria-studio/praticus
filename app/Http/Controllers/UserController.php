<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Coordinator;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = User::with(['coordinator.school']);

        // Se for coordenador, filtrar apenas usuários da sua escola
        if ($user->isCoordinator()) {
            $query->where('coordinator_id', $user->coordinator_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('name')->paginate(15);

        // Coordenadores só veem coordenadores da sua escola
        $coordinators = $user->isCoordinator()
            ? collect([$user->coordinator])
            : Coordinator::active()->orderBy('name')->get();

        return view('users.index', compact('users', 'coordinators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Coordenadores só veem coordenadores da sua escola
        $coordinators = $user->isCoordinator()
            ? collect([$user->coordinator])
            : Coordinator::active()->with('school')->orderBy('name')->get();

        return view('users.create', compact('coordinators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem criar usuários para coordenadores da sua escola
        if ($currentUser->isCoordinator() && $request->coordinator_id) {
            $coordinator = Coordinator::find($request->coordinator_id);
            if (!$coordinator || $coordinator->school_id !== $currentUser->coordinator->school_id) {
                abort(403, 'Você não tem permissão para criar usuários para este coordenador.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,operator,coordinator',
            'status' => 'required|in:ativo,inativo',
            'coordinator_id' => 'nullable|required_if:role,coordinator|exists:coordinators,id',
            'permissions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Se for coordenador, verificar se já existe usuário para este coordenador
            if ($validated['role'] === 'coordinator' && $validated['coordinator_id']) {
                $existingUser = User::where('coordinator_id', $validated['coordinator_id'])->first();
                if ($existingUser) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Já existe um usuário para este coordenador.');
                }

                // Definir permissões padrão para coordenador
                $validated['permissions'] = [
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
                ];

                $validated['is_coordinator'] = true;
            }

            $user = User::create($validated);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'User',
                "Usuário '{$user->name}' foi cadastrado com role '{$user->role}'",
                $user->id
            );

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem ver usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para visualizar este usuário.');
        }

        $user->load('activityLogs');

        // Estatísticas do usuário
        $userStats = [
            'login_count' => $user->activityLogs()->where('type', 'login')->count(),
            'last_activity' => $user->activityLogs()->latest()->first(),
            'created_records' => $user->activityLogs()->where('type', 'create')->count(),
            'updated_records' => $user->activityLogs()->where('type', 'update')->count(),
        ];

        // Atividades recentes
        $recentActivities = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        ActivityLog::log('view', 'User', "Visualizou perfil do usuário: {$user->name}", $user->id);

        return view('users.show', compact('user', 'userStats', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem editar usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        // Coordenadores só veem coordenadores da sua escola
        $coordinators = $currentUser->isCoordinator()
            ? collect([$currentUser->coordinator])
            : Coordinator::active()->with('school')->orderBy('name')->get();

        return view('users.edit', compact('user', 'coordinators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem editar usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,operator,coordinator',
            'status' => 'required|in:ativo,inativo',
            'coordinator_id' => 'nullable|required_if:role,coordinator|exists:coordinators,id',
            'permissions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Se for coordenador, verificar se já existe usuário para este coordenador
            if ($validated['role'] === 'coordinator' && $validated['coordinator_id']) {
                $existingUser = User::where('coordinator_id', $validated['coordinator_id'])
                    ->where('id', '!=', $user->id)
                    ->first();
                if ($existingUser) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Já existe um usuário para este coordenador.');
                }

                // Definir permissões padrão para coordenador se não existirem
                if (!$validated['permissions']) {
                    $validated['permissions'] = [
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
                    ];
                }

                $validated['is_coordinator'] = true;
            } else {
                $validated['is_coordinator'] = false;
                $validated['coordinator_id'] = null;
                $validated['permissions'] = null;
            }

            // Se não for coordenador, limpar campos relacionados
            if ($validated['role'] !== 'coordinator') {
                $validated['is_coordinator'] = false;
                $validated['coordinator_id'] = null;
                $validated['permissions'] = null;
            }

            $user->update($validated);

            // Registrar atividade
            ActivityLog::log(
                'update',
                'User',
                "Usuário '{$user->name}' foi atualizado",
                $user->id
            );

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem deletar usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para deletar este usuário.');
        }

        // Não permitir excluir o próprio usuário
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Você não pode excluir sua própria conta.');
        }

        // Não permitir excluir se for o último admin
        if ($user->isAdmin() && User::admins()->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir o último administrador do sistema.');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $oldValues = $user->toArray();

            // Remover avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            ActivityLog::log('delete', 'User', "Excluiu usuário: {$userName}", null, $oldValues, null);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem alterar senha de usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para alterar a senha deste usuário.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->changePassword($request->password);

        ActivityLog::log('update', 'User', "Alterou senha do usuário: {$user->name}", $user->id);

        return redirect()->back()
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem alterar status de usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para alterar o status deste usuário.');
        }

        // Não permitir desativar o próprio usuário
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Você não pode alterar o status da sua própria conta.');
        }

        $oldStatus = $user->status;
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $user->update(['status' => $newStatus]);

        ActivityLog::log('update', 'User', "Alterou status do usuário {$user->name} de {$oldStatus} para {$newStatus}", $user->id);

        return redirect()->back()
            ->with('success', 'Status do usuário alterado com sucesso!');
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        $currentUser = Auth::user();

        // Coordenadores só podem alterar permissões de usuários da sua escola
        if ($currentUser->isCoordinator() && $user->coordinator_id !== $currentUser->coordinator_id) {
            abort(403, 'Você não tem permissão para alterar as permissões deste usuário.');
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::PERMISSIONS)),
        ]);

        $oldPermissions = $user->permissions;
        $user->update(['permissions' => $request->permissions ?? []]);

        ActivityLog::log('update', 'User', "Atualizou permissões do usuário: {$user->name}", $user->id,
            ['permissions' => $oldPermissions],
            ['permissions' => $user->permissions]
        );

        return redirect()->back()
            ->with('success', 'Permissões atualizadas com sucesso!');
    }

    /**
     * Get users for API
     */
    public function apiIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'role', 'status']);

        return response()->json($users);
    }
}
