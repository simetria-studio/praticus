@extends('layouts.admin')

@section('title', 'Visualizar Professor')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Professores</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">{{ $teacher->name }}</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Professor: {{ $teacher->name }}</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar à Lista
                    </a>
                </div>
            </div>

            <!-- Informações Principais -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                Perfil
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $teacher->photo_url }}"
                                 alt="Foto do Professor"
                                 class="rounded-circle mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">

                            <h4 class="mb-2">{{ $teacher->name }}</h4>
                            <p class="text-muted mb-2">{{ $teacher->specialty }}</p>

                            <span class="badge {{ $teacher->status_badge_class }} fs-6">
                                {{ $teacher->status_label }}
                            </span>

                            @if($teacher->registration)
                                <div class="mt-3">
                                    <small class="text-muted">Matrícula:</small><br>
                                    <strong>{{ $teacher->registration }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Estatísticas Rápidas -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Estatísticas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                @if($teacher->age)
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">{{ $teacher->age }}</h5>
                                        <small class="text-muted">Anos</small>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->years_of_service)
                                <div class="col-6">
                                    <h5 class="text-success mb-1">{{ $teacher->years_of_service }}</h5>
                                    <small class="text-muted">Anos de Serviço</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Dados Pessoais -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle me-2"></i>
                                Dados Pessoais
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Nome Completo</label>
                                        <p class="mb-0">{{ $teacher->name }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">E-mail</label>
                                        <p class="mb-0">
                                            <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">
                                                {{ $teacher->email }}
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                @if($teacher->phone)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Telefone</label>
                                        <p class="mb-0">{{ $teacher->formatted_phone }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->cpf)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">CPF</label>
                                        <p class="mb-0">{{ $teacher->formatted_cpf }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->rg)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">RG</label>
                                        <p class="mb-0">{{ $teacher->rg }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->birth_date)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Data de Nascimento</label>
                                        <p class="mb-0">{{ $teacher->birth_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->gender)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Gênero</label>
                                        <p class="mb-0">{{ $teacher->gender_label }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dados Profissionais -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-briefcase me-2"></i>
                                Dados Profissionais
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Especialidade</label>
                                        <p class="mb-0">
                                            <span class="badge bg-info fs-6">{{ $teacher->specialty }}</span>
                                        </p>
                                    </div>
                                </div>

                                @if($teacher->degree)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Formação Acadêmica</label>
                                        <p class="mb-0">{{ $teacher->degree }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->institution)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Instituição de Formação</label>
                                        <p class="mb-0">{{ $teacher->institution }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->graduation_year)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Ano de Formação</label>
                                        <p class="mb-0">{{ $teacher->graduation_year }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->hiring_date)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Data de Contratação</label>
                                        <p class="mb-0">{{ $teacher->hiring_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->contract_type)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Tipo de Contrato</label>
                                        <p class="mb-0">{{ ucfirst($teacher->contract_type) }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->salary)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Salário</label>
                                        <p class="mb-0">{{ $teacher->formatted_salary }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->workload)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Carga Horária</label>
                                        <p class="mb-0">{{ $teacher->workload }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    @if($teacher->street || $teacher->city)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Endereço
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($teacher->full_address)
                                <p class="mb-0">{{ $teacher->full_address }}</p>
                            @else
                                <div class="row">
                                    @if($teacher->street)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Rua</label>
                                            <p class="mb-0">{{ $teacher->street }}, {{ $teacher->number }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->complement)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Complemento</label>
                                            <p class="mb-0">{{ $teacher->complement }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->neighborhood)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Bairro</label>
                                            <p class="mb-0">{{ $teacher->neighborhood }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->city)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Cidade/Estado</label>
                                            <p class="mb-0">{{ $teacher->city }} - {{ $teacher->state }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->postal_code)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">CEP</label>
                                            <p class="mb-0">{{ $teacher->formatted_postal_code }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Disciplinas e Escolas -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-book me-2"></i>
                                Atuação
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($teacher->subjects && count($teacher->subjects) > 0)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Disciplinas que Leciona</label>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($teacher->subjects as $subject)
                                                <span class="badge bg-primary">{{ $subject }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->schools && count($teacher->schools) > 0)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Escolas onde Atua</label>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($teacher->schools as $schoolId)
                                                @php
                                                    $school = \App\Models\School::find($schoolId);
                                                @endphp
                                                @if($school)
                                                    <span class="badge bg-secondary">{{ $school->name }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Observações -->
                    @if($teacher->observations)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note me-2"></i>
                                Observações
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $teacher->observations }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Histórico de Atividades -->
                    @if($teacher->activityLogs && $teacher->activityLogs->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Histórico de Atividades
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Ação</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teacher->activityLogs->take(10) as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $log->action === 'create' ? 'success' : ($log->action === 'update' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </td>
                                            <td>{{ $log->description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-primary-custom {
    background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%) !important;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.badge {
    font-size: 0.75em;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}
</style>
@endsection
