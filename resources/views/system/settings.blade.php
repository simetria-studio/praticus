@extends('layouts.admin')

@section('title', 'Configurações do Sistema')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('system.dashboard') }}">Sistema</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Configurações</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Configurações do Sistema</h2>
                <a href="{{ route('system.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>

            <div class="row">
                <!-- Configurações da Aplicação -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-cog me-2"></i>
                                Configurações da Aplicação
                            </h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nome da Aplicação</label>
                                    <input type="text" class="form-control" value="{{ config('app.name') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">URL da Aplicação</label>
                                    <input type="text" class="form-control" value="{{ config('app.url') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ambiente</label>
                                    <input type="text" class="form-control" value="{{ config('app.env') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fuso Horário</label>
                                    <input type="text" class="form-control" value="{{ config('app.timezone') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Modo Debug</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ config('app.debug') ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">Debug ativo</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Configurações do Banco de Dados -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-database me-2"></i>
                                Configurações do Banco de Dados
                            </h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Conexão</label>
                                    <input type="text" class="form-control" value="{{ config('database.default') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Host</label>
                                    <input type="text" class="form-control" value="{{ config('database.connections.' . config('database.default') . '.host') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Porta</label>
                                    <input type="text" class="form-control" value="{{ config('database.connections.' . config('database.default') . '.port') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banco de Dados</label>
                                    <input type="text" class="form-control" value="{{ config('database.connections.' . config('database.default') . '.database') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Usuário</label>
                                    <input type="text" class="form-control" value="{{ config('database.connections.' . config('database.default') . '.username') }}" readonly>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Configurações de Email -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                Configurações de Email
                            </h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Driver de Email</label>
                                    <input type="text" class="form-control" value="{{ config('mail.default') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Host SMTP</label>
                                    <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.host') ?? 'Não configurado' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Porta SMTP</label>
                                    <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.port') ?? 'Não configurado' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email de Origem</label>
                                    <input type="text" class="form-control" value="{{ config('mail.from.address') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nome de Origem</label>
                                    <input type="text" class="form-control" value="{{ config('mail.from.name') }}" readonly>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ações do Sistema -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-tools me-2"></i>
                                Ações do Sistema
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <form method="POST" action="{{ route('system.cache.clear') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-broom me-2"></i>
                                        Limpar Cache do Sistema
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('system.backup') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-download me-2"></i>
                                        Criar Backup do Banco
                                    </button>
                                </form>

                                <a href="{{ route('system.backups') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-history me-2"></i>
                                    Gerenciar Backups
                                </a>

                                <hr>

                                <form method="POST" action="{{ route('system.maintenance') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100"
                                            onclick="return confirm('Tem certeza que deseja ativar o modo de manutenção?')">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Modo Manutenção
                                    </button>
                                </form>

                                <a href="{{ route('system.info') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informações do Sistema
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Segurança -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>
                                Configurações de Segurança
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Sessão Lifetime (minutos)</label>
                                        <input type="text" class="form-control" value="{{ config('session.lifetime') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Driver de Sessão</label>
                                        <input type="text" class="form-control" value="{{ config('session.driver') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Secure Cookies</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" {{ config('session.secure') ? 'checked' : '' }} disabled>
                                            <label class="form-check-label">Cookies seguros</label>
                                        </div>
                                    </div>
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

.form-control[readonly] {
    background-color: #f8f9fa;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
