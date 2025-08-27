@extends('layouts.admin')

@section('title', 'Boletim do Aluno')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Boletim</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Boletim Escolar - {{ $student->name }}
                            <span class="badge bg-{{ $academicStatus === 'aprovado' ? 'success' : ($academicStatus === 'recuperacao' ? 'warning' : 'danger') }} ms-2">
                                {{ ucfirst($academicStatus) }}
                            </span>
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>
                                Imprimir
                            </button>
                            <div class="btn-group" role="group">
                                <a href="{{ route('notes.historical-report', $student) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-scroll me-1"></i>
                                    Histórico Completo
                                </a>
                                <a href="{{ route('notes.historical-report.pdf', $student) }}" class="btn btn-outline-primary btn-sm" title="Baixar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                            <a href="{{ route('students.show', $student) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-user me-1"></i>
                                Perfil do Aluno
                            </a>
                            <a href="{{ route('notes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informações do Aluno e Filtro de Ano -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">{{ $student->name }}</h5>
                                            <p class="mb-1"><strong>Matrícula:</strong> {{ $student->enrollment }}</p>
                                            <p class="mb-1"><strong>Série/Turma:</strong> {{ $student->grade_with_class }}</p>
                                            <p class="mb-0"><strong>Escola:</strong> {{ $student->school->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Data de Nascimento:</strong> {{ $student->birth_date->format('d/m/Y') }}</p>
                                            <p class="mb-1"><strong>Idade:</strong> {{ $student->age }} anos</p>
                                            <p class="mb-1"><strong>Status:</strong>
                                                <span class="badge {{ $student->status_badge_class }}">{{ $student->status_label }}</span>
                                            </p>
                                            <p class="mb-0"><strong>Ano Letivo:</strong> {{ $schoolYear }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Desempenho Geral</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="display-4 fw-bold text-{{ $generalAverage >= 6 ? 'success' : ($generalAverage >= 4 ? 'warning' : 'danger') }}">
                                            {{ number_format($generalAverage, 1, ',', '.') }}
                                        </span>
                                        <p class="text-muted mb-0">Média Geral</p>
                                    </div>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $generalAverage >= 6 ? 'success' : ($generalAverage >= 4 ? 'warning' : 'danger') }}"
                                             role="progressbar"
                                             style="width: {{ min($generalAverage * 10, 100) }}%"
                                             aria-valuenow="{{ $generalAverage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="10">
                                        </div>
                                    </div>
                                    <small class="text-muted">Status: {{ ucfirst($academicStatus) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro de Ano Letivo -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <form method="GET" id="yearForm">
                                        <label for="school_year" class="form-label">Ano Letivo</label>
                                        <select class="form-select" id="school_year" name="school_year" onchange="document.getElementById('yearForm').submit()">
                                            @foreach($schoolYears as $year)
                                                <option value="{{ $year }}" {{ $year == $schoolYear ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('notes.create') }}?student_id={{ $student->id }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>
                                    Nova Nota
                                </a>
                                <a href="{{ route('certificates.create') }}?student_id={{ $student->id }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-certificate me-1"></i>
                                    Gerar Certificado
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Boletim de Notas -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                Boletim de Notas - {{ $schoolYear }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 200px;">Disciplina</th>
                                            @foreach($periods as $periodKey => $periodName)
                                                <th class="text-center" style="min-width: 120px;">{{ $periodName }}</th>
                                            @endforeach
                                            <th class="text-center" style="width: 100px;">Média</th>
                                            <th class="text-center" style="width: 100px;">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData as $subjectKey => $subjectData)
                                            <tr>
                                                <td class="fw-bold">{{ $subjectData['name'] }}</td>
                                                @foreach($periods as $periodKey => $periodName)
                                                    @php
                                                        $periodData = $subjectData['periods'][$periodKey] ?? null;
                                                        $periodNotes = $periodData['notes'] ?? collect();
                                                        $periodAverage = $periodData['average'] ?? null;
                                                    @endphp
                                                    <td class="text-center">
                                                        @if($periodNotes->count() > 0)
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle border-0"
                                                                        type="button"
                                                                        data-bs-toggle="dropdown">
                                                                    @if($periodAverage !== null)
                                                                        <span class="fw-bold text-{{ $periodAverage >= 6 ? 'success' : ($periodAverage >= 4 ? 'warning' : 'danger') }}">
                                                                            {{ number_format($periodAverage, 1, ',', '.') }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><h6 class="dropdown-header">Notas do {{ $periodName }}</h6></li>
                                                                    @foreach($periodNotes as $note)
                                                                        <li>
                                                                            <a class="dropdown-item d-flex justify-content-between"
                                                                               href="{{ route('notes.show', $note) }}">
                                                                                <span>{{ $note->evaluation_type_name }}</span>
                                                                                <span class="badge bg-{{ $note->percentage >= 60 ? 'success' : ($note->percentage >= 40 ? 'warning' : 'danger') }}">
                                                                                    {{ number_format($note->grade, 1, ',', '.') }}
                                                                                </span>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li class="text-center">
                                                                        <strong>Média: {{ $periodAverage ? number_format($periodAverage, 1, ',', '.') : '-' }}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    @if($subjectData['average'] !== null)
                                                        <span class="fw-bold fs-5 text-{{ $subjectData['average'] >= 6 ? 'success' : ($subjectData['average'] >= 4 ? 'warning' : 'danger') }}">
                                                            {{ number_format($subjectData['average'], 1, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $status = $subjectData['status'];
                                                        $statusClass = $status === 'aprovado' ? 'success' : ($status === 'recuperacao' ? 'warning' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr>
                                            <th class="fw-bold">MÉDIA GERAL</th>
                                            @foreach($periods as $periodKey => $periodName)
                                                <th class="text-center">
                                                    @php
                                                        $periodGeneralAverage = 0;
                                                        $periodCount = 0;
                                                        foreach($reportData as $subjectData) {
                                                            if(isset($subjectData['periods'][$periodKey]['average']) && $subjectData['periods'][$periodKey]['average'] !== null) {
                                                                $periodGeneralAverage += $subjectData['periods'][$periodKey]['average'];
                                                                $periodCount++;
                                                            }
                                                        }
                                                        $periodGeneralAverage = $periodCount > 0 ? $periodGeneralAverage / $periodCount : null;
                                                    @endphp
                                                    @if($periodGeneralAverage !== null)
                                                        <span class="fw-bold text-{{ $periodGeneralAverage >= 6 ? 'success' : ($periodGeneralAverage >= 4 ? 'warning' : 'danger') }}">
                                                            {{ number_format($periodGeneralAverage, 1, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </th>
                                            @endforeach
                                            <th class="text-center">
                                                <span class="fw-bold fs-4 text-{{ $generalAverage >= 6 ? 'success' : ($generalAverage >= 4 ? 'warning' : 'danger') }}">
                                                    {{ number_format($generalAverage, 1, ',', '.') }}
                                                </span>
                                            </th>
                                            <th class="text-center">
                                                <span class="badge bg-{{ $academicStatus === 'aprovado' ? 'success' : ($academicStatus === 'recuperacao' ? 'warning' : 'danger') }} fs-6">
                                                    {{ ucfirst($academicStatus) }}
                                                </span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Legenda -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-3">Legenda de Avaliação:</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span class="badge bg-success me-2">Aprovado</span> ≥ 6,0
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="badge bg-warning me-2">Recuperação</span> 4,0 - 5,9
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="badge bg-danger me-2">Reprovado</span> < 4,0
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Clique nas notas para ver detalhes
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estatísticas do Aluno -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-success">Disciplinas Aprovadas</h5>
                                            <h2 class="text-success">
                                                {{ collect($reportData)->where('status', 'aprovado')->count() }}
                                            </h2>
                                            <small class="text-muted">de {{ count($reportData) }} disciplinas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-warning">Em Recuperação</h5>
                                            <h2 class="text-warning">
                                                {{ collect($reportData)->where('status', 'recuperacao')->count() }}
                                            </h2>
                                            <small class="text-muted">disciplinas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-danger">Reprovadas</h5>
                                            <h2 class="text-danger">
                                                {{ collect($reportData)->where('status', 'reprovado')->count() }}
                                            </h2>
                                            <small class="text-muted">disciplinas</small>
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

@push('styles')
<style>
@media print {
    .btn, .dropdown-toggle {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-header {
        background-color: #6c757d !important;
        -webkit-print-color-adjust: exact;
    }

    .table {
        font-size: 12px;
    }

    .badge {
        -webkit-print-color-adjust: exact;
    }
}

.dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips se necessário
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
