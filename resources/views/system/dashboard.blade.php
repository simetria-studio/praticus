@extends('layouts.admin')

@section('title', 'Dashboard do Sistema')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Sistema</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Dashboard do Sistema</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('system.settings') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cogs me-1"></i>
                        Configurações
                    </a>
                    <a href="{{ route('system.logs') }}" class="btn btn-outline-info">
                        <i class="fas fa-list me-1"></i>
                        Logs
                    </a>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total de Usuários
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $statistics['total_users'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Usuários Ativos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $statistics['active_users'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Estudantes
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $statistics['total_students'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-graduation-cap fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Escolas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $statistics['total_schools'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-school fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informações do Sistema -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-server me-2"></i>
                                Informações do Sistema
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Versão do Laravel:</td>
                                            <td>{{ $systemInfo['laravel_version'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Versão do PHP:</td>
                                            <td>{{ $systemInfo['php_version'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ambiente:</td>
                                            <td>
                                                <span class="badge bg-{{ config('app.env') === 'production' ? 'success' : 'warning' }}">
                                                    {{ strtoupper(config('app.env')) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Debug:</td>
                                            <td>
                                                <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                                                    {{ config('app.debug') ? 'ATIVADO' : 'DESATIVADO' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Fuso Horário:</td>
                                            <td>{{ config('app.timezone') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Banco de Dados:</td>
                                            <td>{{ $systemInfo['database_version'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uso do Sistema -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Uso do Sistema
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Espaço Total:</td>
                                            <td>{{ $diskUsage['total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Espaço Usado:</td>
                                            <td>{{ $diskUsage['used'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Espaço Livre:</td>
                                            <td>{{ $diskUsage['free'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tamanho do BD:</td>
                                            <td>{{ $diskUsage['database_size'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Cache:</td>
                                            <td>
                                                <form method="POST" action="{{ route('system.cache.clear') }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-broom me-1"></i>
                                                        Limpar Cache
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Backup:</td>
                                            <td>
                                                <form method="POST" action="{{ route('system.backup') }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>
                                                        Criar Backup
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Atividades Recentes
                                </h5>
                                <a href="{{ route('system.logs') }}" class="btn btn-light btn-sm">
                                    Ver Todos os Logs
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($recentActivities->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Usuário</th>
                                                <th>Ação</th>
                                                <th>Descrição</th>
                                                <th>Data/Hora</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentActivities as $activity)
                                            <tr>
                                                <td>
                                                    @if($activity->user)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $activity->user->avatar_url }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 30px; height: 30px;">
                                                            <strong>{{ $activity->user->name }}</strong>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Sistema</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $activity->action === 'create' ? 'success' : ($activity->action === 'update' ? 'warning' : ($activity->action === 'delete' ? 'danger' : 'info')) }}">
                                                        {{ ucfirst($activity->action) }}
                                                    </span>
                                                </td>
                                                <td>{{ $activity->description }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma atividade recente</h5>
                                    <p class="text-muted">As atividades do sistema aparecerão aqui</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-xs {
    font-size: 0.7rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-primary-custom {
    background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%) !important;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.badge {
    font-size: 0.8em;
}
</style>
@endsection
