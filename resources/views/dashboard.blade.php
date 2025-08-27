@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Dashboard</span>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="welcome-section mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="welcome-content">
                <h1 class="h3 mb-2">Bem-vindo de volta!</h1>
                <p class="text-muted mb-0">Aqui está um resumo das atividades do sistema hoje</p>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="welcome-stats">
                <div class="d-inline-block me-3">
                    <span class="fw-bold d-block">0</span>
                    <small class="text-muted">Hoje</small>
                </div>
                <div class="d-inline-block">
                    <span class="fw-bold d-block">0</span>
                    <small class="text-muted">Esta semana</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="h4 mb-1">{{ number_format($stats['students']) }}</h3>
                        <p class="text-muted mb-0">Alunos Cadastrados</p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: {{ min(100, ($stats['students'] / 100) * 100) }}%"></div>
                        </div>
                        <small class="text-success">{{ $stats['students'] > 0 ? 'Atualizado agora' : 'Sistema iniciado' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-scroll text-success fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="h4 mb-1">{{ number_format($stats['notes']) }}</h3>
                        <p class="text-muted mb-0">Notas Lançadas</p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: {{ min(100, ($stats['notes'] / 50) * 100) }}%"></div>
                        </div>
                        <small class="text-success">{{ $stats['notes'] > 0 ? 'Atualizado agora' : 'Sistema iniciado' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-award text-warning fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="h4 mb-1">{{ number_format($stats['certificates']) }}</h3>
                        <p class="text-muted mb-0">Certificados Emitidos</p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: {{ min(100, ($stats['certificates'] / 20) * 100) }}%"></div>
                        </div>
                        <small class="text-success">{{ $stats['certificates'] > 0 ? 'Atualizado agora' : 'Sistema iniciado' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-download text-info fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="h4 mb-1">{{ number_format($activityStats['today']) }}</h3>
                        <p class="text-muted mb-0">Atividades Hoje</p>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: {{ min(100, ($activityStats['today'] / 10) * 100) }}%"></div>
                        </div>
                        <small class="text-success">{{ $activityStats['today'] > 0 ? 'Atualizado agora' : 'Sistema iniciado' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Panels -->
<div class="row g-4">
    <!-- Quick Actions Panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <h3 class="h5 mb-1">Ações Rápidas</h3>
                <p class="text-muted mb-0">Acesse as funcionalidades principais</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('students.create') }}" class="btn btn-outline-primary w-100 text-start">
                            <div class="d-flex align-items-center">
                                <div class="rounded bg-primary bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-user-plus text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Cadastrar Aluno</div>
                                    <small class="text-muted">Adicionar novo estudante</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('notes.index') }}" class="btn btn-outline-success w-100 text-start">
                            <div class="d-flex align-items-center">
                                <div class="rounded bg-success bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-file-signature text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Lançar Notas</div>
                                    <small class="text-muted">Registrar avaliações</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('certificates.create') }}" class="btn btn-outline-warning w-100 text-start">
                            <div class="d-flex align-items-center">
                                <div class="rounded bg-warning bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-certificate text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Emitir Certificado</div>
                                    <small class="text-muted">Certificado digital</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('students.index') }}" class="btn btn-outline-info w-100 text-start">
                            <div class="d-flex align-items-center">
                                <div class="rounded bg-info bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-search-plus text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Buscar Registros</div>
                                    <small class="text-muted">Localizar documentos</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Stats Panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <h3 class="h5 mb-1">Estatísticas de Atividades</h3>
                <p class="text-muted mb-0">Últimos 7 dias</p>
            </div>
            <div class="card-body">
                @forelse($activityTypes as $type)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            @php
                                $typeLabels = [
                                    'create' => ['Criação', 'fas fa-plus-circle text-success'],
                                    'update' => ['Atualização', 'fas fa-edit text-warning'],
                                    'delete' => ['Exclusão', 'fas fa-trash text-danger'],
                                    'view' => ['Visualização', 'fas fa-eye text-info'],
                                    'download' => ['Download', 'fas fa-download text-primary']
                                ];
                                $label = $typeLabels[$type->type] ?? ['Outro', 'fas fa-circle text-secondary'];
                            @endphp
                            <div class="rounded bg-light p-2 me-3">
                                <i class="{{ $label[1] }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $label[0] }}</h6>
                                <small class="text-muted">{{ ucfirst($type->type) }}</small>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $type->count }}</span>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-chart-bar text-muted fa-2x mb-2"></i>
                        <p class="text-muted mb-0">Sem atividades nos últimos 7 dias</p>
                    </div>
                @endforelse

                <div class="mt-4 pt-3 border-top">
                    <div class="row text-center">
                        <div class="col">
                            <h6 class="mb-0">{{ $activityStats['today'] }}</h6>
                            <small class="text-muted">Hoje</small>
                        </div>
                        <div class="col">
                            <h6 class="mb-0">{{ $activityStats['week'] }}</h6>
                            <small class="text-muted">Esta semana</small>
                        </div>
                        <div class="col">
                            <h6 class="mb-0">{{ $activityStats['total'] }}</h6>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline Panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <h3 class="h5 mb-1">Atividades Recentes</h3>
                <p class="text-muted mb-0">Últimas ações no sistema</p>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    @forelse($recentActivities as $activity)
                        <div class="d-flex {{ !$loop->last ? 'mb-3' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-{{ $activity->color }} bg-opacity-10 p-2">
                                    <i class="{{ $activity->icon }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                <p class="text-muted mb-1">
                                    Por <strong>{{ $activity->user_name }}</strong>
                                    @if($activity->model_id)
                                        <span class="badge bg-light text-dark ms-1">{{ $activity->model }} #{{ $activity->model_id }}</span>
                                    @endif
                                </p>
                                <small class="text-muted">{{ $activity->time_ago }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-clock text-muted fa-2x mb-3"></i>
                            <h6 class="text-muted">Nenhuma atividade recente</h6>
                            <p class="text-muted mb-0">As atividades aparecerão aqui conforme o uso do sistema</p>
                        </div>
                    @endforelse
                </div>
                
                @if($recentActivities->count() > 0)
                    <div class="text-center mt-3 pt-3 border-top">
                        <button class="btn btn-outline-primary btn-sm" onclick="loadMoreActivities()">
                            <i class="fas fa-plus me-1"></i> Ver mais atividades
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Number animation
    function animateNumbers() {
        const numbers = document.querySelectorAll('.h4');

        numbers.forEach(number => {
            if (!isNaN(parseInt(number.textContent))) {
                const target = parseInt(number.textContent.replace(/,/g, ''));
                let current = 0;
                const increment = target / 60;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    number.textContent = Math.floor(current).toLocaleString();
                }, 25);
            }
        });
    }

    // Start animation after delay
    setTimeout(animateNumbers, 500);

    // Função para carregar mais atividades
    window.loadMoreActivities = function() {
        // Implementar carregamento via AJAX se necessário
        alert('Funcionalidade em desenvolvimento');
    };
});
</script>
@endpush
