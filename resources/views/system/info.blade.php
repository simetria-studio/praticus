@extends('layouts.admin')

@section('title', 'Informações do Sistema')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('system.dashboard') }}">Sistema</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Informações</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Informações do Sistema</h2>
                <a href="{{ route('system.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>

            <div class="row">
                <!-- Informações do PHP -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fab fa-php me-2"></i>
                                Informações do PHP
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Versão do PHP:</td>
                                            <td>{{ $phpInfo['version'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">SAPI:</td>
                                            <td>{{ $phpInfo['sapi'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Limite de Memória:</td>
                                            <td>{{ $phpInfo['memory_limit'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tempo Máximo de Execução:</td>
                                            <td>{{ $phpInfo['max_execution_time'] }}s</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tamanho Máximo de Upload:</td>
                                            <td>{{ $phpInfo['upload_max_filesize'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tamanho Máximo de POST:</td>
                                            <td>{{ $phpInfo['post_max_size'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Diretório Temporário:</td>
                                            <td><code>{{ $phpInfo['upload_tmp_dir'] ?: sys_get_temp_dir() }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Laravel -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fab fa-laravel me-2"></i>
                                Informações do Laravel
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Versão do Laravel:</td>
                                            <td>{{ $laravelInfo['version'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ambiente:</td>
                                            <td>
                                                <span class="badge bg-{{ $laravelInfo['environment'] === 'production' ? 'success' : 'warning' }}">
                                                    {{ strtoupper($laravelInfo['environment']) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Debug:</td>
                                            <td>
                                                <span class="badge bg-{{ $laravelInfo['debug'] ? 'danger' : 'success' }}">
                                                    {{ $laravelInfo['debug'] ? 'ATIVADO' : 'DESATIVADO' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">URL da Aplicação:</td>
                                            <td><code>{{ $laravelInfo['url'] }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Fuso Horário:</td>
                                            <td>{{ $laravelInfo['timezone'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Locale:</td>
                                            <td>{{ $laravelInfo['locale'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Cache Driver:</td>
                                            <td>{{ $laravelInfo['cache_driver'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Session Driver:</td>
                                            <td>{{ $laravelInfo['session_driver'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informações do Servidor -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-server me-2"></i>
                                Informações do Servidor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Sistema Operacional:</td>
                                            <td>{{ $serverInfo['os'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Servidor Web:</td>
                                            <td>{{ $serverInfo['server_software'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Host:</td>
                                            <td>{{ $serverInfo['http_host'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Porta:</td>
                                            <td>{{ $serverInfo['server_port'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Protocolo:</td>
                                            <td>{{ $serverInfo['server_protocol'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Documento Root:</td>
                                            <td><code>{{ $serverInfo['document_root'] }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Uptime do Servidor:</td>
                                            <td>{{ $serverInfo['uptime'] ?? 'Não disponível' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Banco de Dados -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-database me-2"></i>
                                Informações do Banco de Dados
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">SGBD:</td>
                                            <td>{{ $databaseInfo['driver'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Versão:</td>
                                            <td>{{ $databaseInfo['version'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Host:</td>
                                            <td>{{ $databaseInfo['host'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Porta:</td>
                                            <td>{{ $databaseInfo['port'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Banco de Dados:</td>
                                            <td>{{ $databaseInfo['database'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Usuário:</td>
                                            <td>{{ $databaseInfo['username'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tamanho do Banco:</td>
                                            <td>{{ $databaseInfo['size'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Extensões PHP -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-puzzle-piece me-2"></i>
                                Extensões PHP Carregadas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($phpExtensions as $index => $extension)
                                    <div class="col-md-3 col-sm-4 col-6 mb-2">
                                        <span class="badge bg-light text-dark w-100 text-start">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            {{ $extension }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas de Uso -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Estatísticas de Uso do Disco
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-primary">{{ $diskUsage['total'] }}</h4>
                                        <p class="text-muted mb-0">Espaço Total</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-success">{{ $diskUsage['free'] }}</h4>
                                        <p class="text-muted mb-0">Espaço Livre</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-warning">{{ $diskUsage['used'] }}</h4>
                                        <p class="text-muted mb-0">Espaço Usado</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-info">{{ $diskUsage['database_size'] }}</h4>
                                        <p class="text-muted mb-0">Tamanho do Banco</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Barra de Progresso -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Uso do Disco</span>
                                    <span class="text-muted">
                                        {{ number_format((($diskUsage['total_bytes'] - $diskUsage['free_bytes']) / $diskUsage['total_bytes']) * 100, 1) }}%
                                    </span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning"
                                         style="width: {{ number_format((($diskUsage['total_bytes'] - $diskUsage['free_bytes']) / $diskUsage['total_bytes']) * 100, 1) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    word-break: break-all;
}

.badge {
    font-size: 0.8em;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}

h4 {
    font-weight: 700;
    margin-bottom: 0.5rem;
}
</style>
@endsection
