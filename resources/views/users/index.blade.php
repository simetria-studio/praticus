@extends('layouts.admin')

@section('title', 'Usuários')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Usuários</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Usuários</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Usuário
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Função</th>
                            <th>Status</th>
                            <th>Coordenador/Escola</th>
                            <th>Último Acesso</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <img src="{{ $user->avatar_url }}"
                                             alt="Avatar"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Administrador</span>
                                        @break
                                    @case('manager')
                                        <span class="badge bg-warning">Gerente</span>
                                        @break
                                    @case('operator')
                                        <span class="badge bg-info">Operador</span>
                                        @break
                                    @case('coordinator')
                                        <span class="badge bg-success">Coordenador</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <span class="badge {{ $user->status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->status === 'ativo' ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                @if($user->isCoordinator() && $user->coordinator)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->coordinator->photo_url }}"
                                             alt="Foto do coordenador"
                                             class="rounded-circle me-2"
                                             style="width: 24px; height: 24px; object-fit: cover;">
                                        <div>
                                            <div class="fw-medium">{{ $user->coordinator->name }}</div>
                                            <small class="text-muted">{{ $user->coordinator->school->name }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_school_access)
                                    <small class="text-muted">
                                        {{ $user->last_school_access->diffForHumans() }}
                                    </small>
                                @else
                                    <span class="text-muted">Nunca</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST"
                                              action="{{ route('users.destroy', $user) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja remover este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Remover">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">Nenhum usuário encontrado.</p>
                                <a href="{{ route('users.create') }}" class="text-decoration-none">Cadastre o primeiro usuário</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
