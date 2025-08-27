@extends('layouts.admin')

@section('title', 'Perfil do Usuário')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Perfil</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Perfil do Usuário
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            @if(Auth::user()->isAdmin() && $user->id !== Auth::id())
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-1"></i>
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-toggle-on me-2"></i>
                                                {{ $user->status === 'active' ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger"
                                                onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                            <i class="fas fa-trash me-2"></i>
                                            Excluir
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Informações do Perfil -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <img src="{{ $user->avatar_url }}"
                                     alt="Avatar de {{ $user->name }}"
                                     class="rounded-circle mb-3"
                                     style="width: 200px; height: 200px; object-fit: cover;">

                                <h3 class="mb-1">{{ $user->name }}</h3>
                                <p class="text-muted">
                            @switch($user->role)
                                @case('admin')
                                    Administrador
                                    @break
                                @case('manager')
                                    Gerente
                                    @break
                                @case('operator')
                                    Operador
                                    @break
                                @case('coordinator')
                                    Coordenador
                                    @break
                                @default
                                    {{ ucfirst($user->role) }}
                            @endswitch
                        </p>

                                <span class="badge {{ $user->status_badge_class }} fs-6">
                                    {{ $user->status_name }}
                                </span>
                            </div>

                            <!-- Informações de Contato -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-address-card me-2"></i>
                                        Informações de Contato
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">E-mail</label>
                                        <div class="fw-bold">{{ $user->email }}</div>
                                    </div>

                                    @if($user->phone)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Telefone</label>
                                        <div class="fw-bold">{{ $user->phone }}</div>
                                    </div>
                                    @endif

                                    @if($user->department)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Departamento</label>
                                        <div class="fw-bold">{{ $user->department }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes e Estatísticas -->
                        <div class="col-md-8">
                            <!-- Estatísticas -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-1">{{ $userStats['login_count'] ?? 0 }}</h4>
                                            <small>Total de Logins</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-1">{{ $userStats['created_records'] ?? 0 }}</h4>
                                            <small>Registros Criados</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-1">{{ $userStats['updated_records'] ?? 0 }}</h4>
                                            <small>Atualizações</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-1">
                                                {{ $user->created_at->diffInDays(now()) }}
                                            </h4>
                                            <small>Dias no Sistema</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações da Conta -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações da Conta
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Data de Cadastro</label>
                                                <div class="fw-bold">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Última Atualização</label>
                                                <div class="fw-bold">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        @if($user->last_login_at)
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Último Login</label>
                                                <div class="fw-bold">{{ $user->last_login_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($user->password_changed_at)
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Última Troca de Senha</label>
                                                <div class="fw-bold">{{ $user->password_changed_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Atividades Recentes -->
                            @if(isset($recentActivities) && $recentActivities->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>
                                        Atividades Recentes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        @foreach($recentActivities->take(10) as $activity)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o usuário <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0.25rem;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    border-left: 2px solid #e9ecef;
    padding-left: 1rem;
    padding-bottom: 1rem;
}

.timeline-item:last-child .timeline-content {
    border-left: none;
}
</style>

<script>
function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
