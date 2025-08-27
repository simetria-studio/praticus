@extends('layouts.admin')

@section('title', 'Histórico Escolar')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Histórico Escolar</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-scroll me-2"></i>
                            Histórico Escolar - {{ $student->name }}
                            <span class="badge bg-{{ $overallStats['current_status'] === 'ativo' ? 'success' : ($overallStats['current_status'] === 'formado' ? 'primary' : 'secondary') }} ms-2">
                                {{ ucfirst($overallStats['current_status']) }}
                            </span>
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>
                                Imprimir
                            </button>
                            <a href="{{ route('notes.historical-report.pdf', $student) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i>
                                Baixar PDF
                            </a>
                            <a href="{{ route('notes.student-report', $student) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-chart-bar me-1"></i>
                                Boletim Atual
                            </a>
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
                    <!-- Cabeçalho Oficial do Histórico -->
                    <div class="text-center mb-4 p-4 border">
                        <h3 class="fw-bold text-primary">HISTÓRICO ESCOLAR</h3>
                        <p class="mb-1 fw-bold">{{ strtoupper($student->school->name) }}</p>
                        <p class="mb-1">{{ $student->school->full_address }}</p>
                        @if($student->school->cnpj)
                            <p class="mb-1">CNPJ: {{ $student->school->formatted_cnpj }}</p>
                        @endif
                        <small class="text-muted">Documento emitido em {{ now()->format('d/m/Y') }}</small>
                    </div>

                    <!-- Dados Pessoais do Aluno -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Dados Pessoais
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Nome:</strong> {{ $student->name }}</p>
                                            <p class="mb-2"><strong>Matrícula:</strong> {{ $student->enrollment }}</p>
                                            <p class="mb-2"><strong>CPF:</strong> {{ $student->formatted_cpf ?: 'Não informado' }}</p>
                                            <p class="mb-2"><strong>RG:</strong> {{ $student->rg ?: 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Data de Nascimento:</strong> {{ $student->birth_date->format('d/m/Y') }}</p>
                                            <p class="mb-2"><strong>Naturalidade:</strong> {{ $student->city }}/{{ $student->state }}</p>
                                            <p class="mb-2"><strong>Nacionalidade:</strong> {{ $student->country }}</p>
                                            <p class="mb-2"><strong>Filiação:</strong> {{ $student->guardian_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Resumo Acadêmico Geral</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="display-4 fw-bold text-{{ $overallStats['overall_average'] >= 6 ? 'success' : ($overallStats['overall_average'] >= 4 ? 'warning' : 'danger') }}">
                                            {{ number_format($overallStats['overall_average'], 1, ',', '.') }}
                                        </span>
                                        <p class="text-muted mb-0">Média Geral</p>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h6 class="text-success">{{ $overallStats['total_approved'] }}</h6>
                                            <small class="text-muted">Aprovadas</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-primary">{{ $overallStats['total_years'] }}</h6>
                                            <small class="text-muted">Anos</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-danger">{{ $overallStats['total_failed'] }}</h6>
                                            <small class="text-muted">Reprovadas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Histórico por Ano Letivo -->
                    @foreach($historicalData as $yearData)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Ano Letivo: {{ $yearData['year'] }}
                                    </h5>
                                    <div class="d-flex gap-3">
                                        <span class="badge bg-{{ $yearData['general_average'] >= 6 ? 'success' : ($yearData['general_average'] >= 4 ? 'warning' : 'danger') }} fs-6">
                                            Média: {{ number_format($yearData['general_average'], 1, ',', '.') }}
                                        </span>
                                        <span class="badge bg-{{ $yearData['academic_status'] === 'aprovado' ? 'success' : ($yearData['academic_status'] === 'recuperacao' ? 'warning' : 'danger') }} fs-6">
                                            {{ ucfirst($yearData['academic_status']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($yearData['subjects']) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 250px;">Disciplina</th>
                                                    <th class="text-center" style="width: 100px;">Média Final</th>
                                                    <th class="text-center" style="width: 120px;">Situação</th>
                                                    <th class="text-center" style="width: 100px;">Carga Horária</th>
                                                    <th class="text-center">Detalhes por Período</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($yearData['subjects'] as $subjectKey => $subjectData)
                                                    <tr>
                                                        <td class="fw-bold">{{ $subjectData['name'] }}</td>
                                                        <td class="text-center">
                                                            @if($subjectData['average'] !== null)
                                                                <span class="fw-bold text-{{ $subjectData['average'] >= 6 ? 'success' : ($subjectData['average'] >= 4 ? 'warning' : 'danger') }}">
                                                                    {{ number_format($subjectData['average'], 1, ',', '.') }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-{{ $subjectData['status'] === 'aprovado' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($subjectData['status']) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="text-muted">{{ $subjectData['notes_count'] * 20 }}h</span>
                                                        </td>
                                                        <td>
                                                            @if(count($subjectData['periods']) > 0)
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($subjectData['periods'] as $periodKey => $periodData)
                                                                        <small class="badge bg-light text-dark border">
                                                                            {{ $periodData['name'] }}:
                                                                            <span class="text-{{ $periodData['average'] >= 6 ? 'success' : ($periodData['average'] >= 4 ? 'warning' : 'danger') }}">
                                                                                {{ $periodData['average'] ? number_format($periodData['average'], 1, ',', '.') : '-' }}
                                                                            </span>
                                                                        </small>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted">Sem períodos registrados</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-secondary">
                                                <tr>
                                                    <th>RESUMO DO ANO</th>
                                                    <th class="text-center">
                                                        <span class="fw-bold text-{{ $yearData['general_average'] >= 6 ? 'success' : ($yearData['general_average'] >= 4 ? 'warning' : 'danger') }}">
                                                            {{ number_format($yearData['general_average'], 1, ',', '.') }}
                                                        </span>
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="badge bg-{{ $yearData['academic_status'] === 'aprovado' ? 'success' : ($yearData['academic_status'] === 'recuperacao' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($yearData['academic_status']) }}
                                                        </span>
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="fw-bold">{{ $yearData['total_subjects'] * 160 }}h</span>
                                                    </th>
                                                    <th class="text-center">
                                                        <small>
                                                            <span class="text-success">{{ $yearData['approved_subjects'] }} aprovadas</span> |
                                                            <span class="text-danger">{{ $yearData['failed_subjects'] }} reprovadas</span>
                                                        </small>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Nenhuma disciplina registrada para o ano letivo de {{ $yearData['year'] }}.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Resumo Final -->
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Resumo Final do Histórico Escolar
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card text-center bg-primary text-white">
                                        <div class="card-body">
                                            <h4>{{ $overallStats['total_years'] }}</h4>
                                            <p class="mb-0">Anos Cursados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-success text-white">
                                        <div class="card-body">
                                            <h4>{{ $overallStats['total_approved'] }}</h4>
                                            <p class="mb-0">Disciplinas Aprovadas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-danger text-white">
                                        <div class="card-body">
                                            <h4>{{ $overallStats['total_failed'] }}</h4>
                                            <p class="mb-0">Disciplinas Reprovadas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-{{ $overallStats['overall_average'] >= 6 ? 'success' : ($overallStats['overall_average'] >= 4 ? 'warning' : 'danger') }} text-white">
                                        <div class="card-body">
                                            <h4>{{ number_format($overallStats['overall_average'], 1, ',', '.') }}</h4>
                                            <p class="mb-0">Média Geral</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline Acadêmica -->
                            <div class="mt-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-timeline me-2"></i>
                                    Timeline Acadêmica
                                </h6>
                                <div class="timeline">
                                    @foreach($academicSummary as $index => $summary)
                                        <div class="timeline-item {{ $index === 0 ? 'timeline-item-current' : '' }}">
                                            <div class="timeline-marker bg-{{ $summary['status'] === 'aprovado' ? 'success' : ($summary['status'] === 'recuperacao' ? 'warning' : 'danger') }}">
                                                {{ $summary['year'] }}
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Ano Letivo {{ $summary['year'] }}</h6>
                                                        <p class="text-muted mb-1">
                                                            {{ $summary['subjects_count'] }} disciplinas |
                                                            Média: {{ number_format($summary['average'], 1, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <span class="badge bg-{{ $summary['status'] === 'aprovado' ? 'success' : ($summary['status'] === 'recuperacao' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($summary['status']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Informações Complementares -->
                            <div class="mt-4 p-3 bg-light border-start border-primary border-4">
                                <h6>Informações Complementares:</h6>
                                <ul class="mb-0">
                                    <li>Este histórico escolar contém {{ $overallStats['total_subjects_studied'] }} registros de disciplinas cursadas</li>
                                    <li>Período de estudos: {{ min($schoolYears) }} a {{ max($schoolYears) }}</li>
                                    <li>Status atual do aluno: <strong>{{ ucfirst($overallStats['current_status']) }}</strong></li>
                                    <li>Histórico gerado em: {{ now()->format('d/m/Y às H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Rodapé Oficial -->
                    <div class="text-center mt-4 p-3 border-top">
                        <p class="mb-1"><strong>{{ $student->school->name }}</strong></p>
                        <p class="mb-1">{{ $student->school->full_address }}</p>
                        <p class="mb-0 text-muted">
                            Este documento foi gerado eletronicamente em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    /* Ocultar elementos da interface administrativa */
    .modern-sidebar,
    .panel-header,
    .btn,
    .dropdown-toggle,
    .breadcrumb,
    .sidebar-toggle {
        display: none !important;
    }

    /* Ajustar layout principal para impressão */
    .main-panel {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .admin-panel {
        display: block !important;
    }

    /* Garantir que o container seja visível */
    .container-fluid {
        padding: 0 !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Garantir que o conteúdo principal seja sempre visível */
    .card,
    .card-body,
    .card-header {
        display: block !important;
        visibility: visible !important;
        page-break-inside: avoid;
        margin: 0 !important;
    }

    /* Cabeçalho oficial do histórico - SEMPRE visível */
    .card-body > .text-center:first-child {
        display: block !important;
        visibility: visible !important;
        margin-bottom: 20px !important;
        border: 2px solid #000 !important;
        padding: 15px !important;
        page-break-after: avoid !important;
    }

    /* Títulos e cabeçalhos */
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border-bottom: 1px solid #000 !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Ocultar apenas os botões do cabeçalho, manter título */
    .card-header .btn,
    .card-header .dropdown-toggle,
    .card-header .d-flex .btn {
        display: none !important;
    }

    .card-header h4,
    .card-header h5 {
        display: block !important;
        visibility: visible !important;
        color: #000 !important;
    }

    .card-body {
        padding: 15px !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Dados pessoais e resumo - sempre visíveis */
    .row,
    .col-md-8,
    .col-md-4,
    .col-md-6,
    .col-md-3 {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        float: none !important;
        margin-bottom: 10px !important;
    }

    /* Ajustar resumo acadêmico para lado a lado */
    .col-md-8 {
        width: 65% !important;
        float: left !important;
        display: block !important;
    }

    .col-md-4 {
        width: 33% !important;
        float: right !important;
        display: block !important;
    }

    /* Clearfix */
    .row::after {
        content: "";
        display: table;
        clear: both;
    }

    /* Ajustes específicos para tabelas */
    .table {
        font-size: 11px !important;
        border-collapse: collapse !important;
        display: table !important;
        visibility: visible !important;
        width: 100% !important;
    }

    .table th,
    .table td {
        border: 1px solid #000 !important;
        padding: 4px 6px !important;
        display: table-cell !important;
        visibility: visible !important;
    }

    .table th {
        background-color: #f0f0f0 !important;
        font-weight: bold !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Badges e cores */
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        display: inline !important;
        visibility: visible !important;
    }

    /* Timeline */
    .timeline-item {
        page-break-inside: avoid;
        margin-bottom: 10px !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Cores de texto específicas para impressão */
    .text-success,
    .text-danger,
    .text-warning,
    .text-primary,
    .text-info {
        color: #000 !important;
    }

    /* Ajustar fonte geral */
    body {
        font-size: 12px !important;
        line-height: 1.3 !important;
        color: #000 !important;
    }

    /* Cabeçalhos */
    h1, h2, h3, h4, h5, h6 {
        color: #000 !important;
        page-break-after: avoid !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Parágrafos e textos */
    p, div, span, strong, small {
        display: block !important;
        visibility: visible !important;
        color: #000 !important;
    }

    /* Elementos inline */
    span, small, strong, em, i {
        display: inline !important;
    }

    /* Quebras de página */
    .card:not(:first-child) {
        page-break-before: avoid !important;
    }

    /* Primeira página - garantir conteúdo */
    .card:first-child {
        margin-top: 0 !important;
        page-break-before: avoid !important;
    }

    /* Garantir borda nos cards para impressão */
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    /* Ajustar margens da página */
    @page {
        margin: 1.5cm 1cm 1.5cm 1cm;
        size: A4;
    }

    /* Remover qualquer espaçamento que possa causar página em branco */
    body, html {
        margin: 0 !important;
        padding: 0 !important;
    }

    .container-fluid .row .col-12 .card:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 40px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 0;
    bottom: -20px;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
    color: white;
    z-index: 1;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-item-current .timeline-content {
    border-left-color: #28a745;
    background-color: #f8fff9;
}

@media print {
    .timeline-marker {
        background-color: #000 !important;
        color: #fff !important;
        border: 1px solid #000 !important;
    }

    .timeline-content {
        background-color: #f8f9fa !important;
        border-left: 3px solid #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Melhorar a impressão
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });

    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
});
</script>
@endpush
@endsection
