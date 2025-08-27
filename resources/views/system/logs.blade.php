@extends('layouts.admin')

@section('title', 'Logs do Sistema')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('system.dashboard') }}">Sistema</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Logs</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Logs do Sistema</h2>
                <a href="{{ route('system.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('system.logs') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="action" class="form-label">Ação</label>
                                    <select class="form-select" id="action" name="action">
                                        <option value="">Todas as ações</option>
                                        <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                                        <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                                        <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Criação</option>
                                        <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Atualização</option>
                                        <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Exclusão</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Modelo</label>
                                    <select class="form-select" id="model" name="model">
                                        <option value="">Todos os modelos</option>
                                        <option value="User" {{ request('model') === 'User' ? 'selected' : '' }}>Usuários</option>
                                        <option value="Student" {{ request('model') === 'Student' ? 'selected' : '' }}>Estudantes</option>
                                        <option value="School" {{ request('model') === 'School' ? 'selected' : '' }}>Escolas</option>
                                        <option value="Note" {{ request('model') === 'Note' ? 'selected' : '' }}>Notas</option>
                                        <option value="System" {{ request('model') === 'System' ? 'selected' : '' }}>Sistema</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Usuário</label>
                                    <select class="form-select" id="user_id" name="user_id">
                                        <option value="">Todos os usuários</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('system.logs') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Limpar Filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Logs -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Atividades Registradas
                        </h5>
                        <div class="badge bg-light text-dark">
                            Total: {{ $activities->total() }}
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($activities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%">Usuário</th>
                                        <th style="width: 10%">Ação</th>
                                        <th style="width: 10%">Modelo</th>
                                        <th style="width: 40%">Descrição</th>
                                        <th style="width: 10%">IP</th>
                                        <th style="width: 15%">Data/Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activities as $activity)
                                    <tr>
                                        <td>
                                            @if($activity->user)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $activity->user->avatar_url }}"
                                                         class="rounded-circle me-2"
                                                         style="width: 30px; height: 30px;">
                                                    <div>
                                                        <div class="fw-bold">{{ $activity->user->name }}</div>
                                                        <small class="text-muted">
                                                @switch($activity->user->role)
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
                                                        {{ ucfirst($activity->user->role) }}
                                                @endswitch
                                            </small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{
                                                $activity->action === 'create' ? 'success' :
                                                ($activity->action === 'update' ? 'warning' :
                                                ($activity->action === 'delete' ? 'danger' :
                                                ($activity->action === 'login' ? 'info' : 'secondary')))
                                            }}">
                                                {{ ucfirst($activity->action) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $activity->model }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;"
                                                 title="{{ $activity->description }}">
                                                {{ $activity->description }}
                                            </div>
                                        </td>
                                        <td>
                                            <code class="small">{{ $activity->ip_address ?? '-' }}</code>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div>{{ $activity->created_at->format('d/m/Y') }}</div>
                                                <div class="text-muted">{{ $activity->created_at->format('H:i:s') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="card-footer">
                            {{ $activities->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma atividade encontrada</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['action', 'model', 'user_id', 'date']))
                                    Tente ajustar os filtros ou limpar todos os filtros
                                @else
                                    As atividades do sistema aparecerão aqui conforme são executadas
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-custom {
    background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%) !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75em;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}
</style>
@endsection
