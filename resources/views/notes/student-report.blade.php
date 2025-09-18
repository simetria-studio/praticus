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
                    <!-- Informações do Aluno -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-user me-2"></i>
                                        Informações do Aluno
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nome:</strong> {{ $student->name }}</p>
                                            <p class="mb-1"><strong>Matrícula:</strong> {{ $student->registration }}</p>
                                            <p class="mb-1"><strong>Data de Nascimento:</strong> {{ $student->birth_date ? $student->birth_date->format('d/m/Y') : '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Escola:</strong> {{ $student->school->name ?? '-' }}</p>
                                            <p class="mb-1"><strong>Turma:</strong> {{ $student->schoolClass->name ?? '-' }}</p>
                                            <p class="mb-1"><strong>Ano Letivo:</strong> {{ $schoolYear }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Média Geral</h5>
                                    <h2 class="text-{{ $generalAverage >= 6 ? 'success' : ($generalAverage >= 4 ? 'warning' : 'danger') }}">
                                        {{ number_format($generalAverage, 1, ',', '.') }}
                                    </h2>
                                    <p class="text-muted mb-0">Ano {{ $schoolYear }}</p>
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
                                <table class="table table-bordered mb-0" style="font-size: 12px;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="3" class="text-center align-middle" style="width: 200px;">
                                                COMPONENTES CURRICULARES
                                            </th>

                                            <!-- 1º SEMESTRE -->
                                            <th colspan="6" class="text-center">
                                                BOLETIM DE AVALIAÇÕES - 1º SEMESTRE
                                            </th>

                                            <!-- 2º SEMESTRE -->
                                            <th colspan="6" class="text-center">
                                                BOLETIM DE AVALIAÇÕES - 2º SEMESTRE
                                            </th>

                                            <!-- COLUNAS FINAIS -->
                                            <th colspan="4" class="text-center">
                                                RESULTADO FINAL
                                            </th>
                                        </tr>
                                        <tr>
                                            <!-- 1º SEMESTRE -->
                                            <th colspan="4" class="text-center">NOTA DAS AVALIAÇÕES</th>
                                            <th class="text-center">REC.</th>
                                            <th class="text-center">MÉDIA 1º SEME.</th>

                                            <!-- 2º SEMESTRE -->
                                            <th colspan="4" class="text-center">NOTA DAS AVALIAÇÕES</th>
                                            <th class="text-center">REC.</th>
                                            <th class="text-center">MÉDIA 2º SEME.</th>

                                            <!-- RESULTADO FINAL -->
                                            <th class="text-center">PONTOS 1º E 2º SEME.</th>
                                            <th class="text-center">MÉDIA 1º E 2º SEME.</th>
                                            <th class="text-center">PROVA FINAL</th>
                                            <th class="text-center">MÉDIA GERAL</th>
                                        </tr>
                                        <tr>
                                            <!-- 1º SEMESTRE -->
                                            <th class="text-center">1ª</th>
                                            <th class="text-center">2ª</th>
                                            <th class="text-center">3ª</th>
                                            <th class="text-center">4ª</th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>

                                            <!-- 2º SEMESTRE -->
                                            <th class="text-center">5ª</th>
                                            <th class="text-center">6ª</th>
                                            <th class="text-center">7ª</th>
                                            <th class="text-center">8ª</th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>

                                            <!-- RESULTADO FINAL -->
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData as $subjectKey => $subjectData)
                                            <tr>
                                                <!-- Nome da Disciplina -->
                                                <td class="fw-bold">{{ $subjectData['name'] }}</td>

                                                <!-- 1º SEMESTRE -->
                                                @php
                                                    $semester1Notes = [];
                                                    $semester1Recovery = null;
                                                    $semester1Average = 0;

                                                    // Buscar notas do 1º semestre
                                                    foreach(['1_ava', '2_ava', '3_ava', '4_ava'] as $period) {
                                                        $periodData = $subjectData['periods'][$period] ?? null;
                                                        $semester1Notes[] = $periodData['average'] ?? 0;
                                                    }

                                                    // Buscar recuperação do 1º semestre
                                                    $recoveryData = $subjectData['periods']['recuperacao_1_semestre'] ?? null;
                                                    $semester1Recovery = $recoveryData['average'] ?? null;

                                                    // Calcular média do 1º semestre (média das 4 AVAs)
                                                    $validNotes = array_filter($semester1Notes, function($note) { return $note > 0; });
                                                    if(!empty($validNotes)) {
                                                        $semester1Average = array_sum($validNotes) / count($validNotes);
                                                    }
                                                    // Se houver recuperação e ela for maior que a média, usar a recuperação
                                                    if($semester1Recovery !== null && $semester1Recovery > $semester1Average) {
                                                        $semester1Average = $semester1Recovery;
                                                    }
                                                @endphp

                                                @foreach($semester1Notes as $grade)
                                                    <td class="text-center">{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                                                @endforeach
                                                <td class="text-center">{{ $semester1Recovery ? number_format($semester1Recovery, 1, ',', '.') : '-' }}</td>
                                                <td class="text-center fw-bold">{{ $semester1Average > 0 ? number_format($semester1Average, 1, ',', '.') : '-' }}</td>

                                                <!-- 2º SEMESTRE -->
                                                @php
                                                    $semester2Notes = [];
                                                    $semester2Recovery = null;
                                                    $semester2Average = 0;

                                                    // Buscar notas do 2º semestre
                                                    foreach(['5_ava', '6_ava', '7_ava', '8_ava'] as $period) {
                                                        $periodData = $subjectData['periods'][$period] ?? null;
                                                        $semester2Notes[] = $periodData['average'] ?? 0;
                                                    }

                                                    // Buscar recuperação do 2º semestre
                                                    $recoveryData = $subjectData['periods']['recuperacao_2_semestre'] ?? null;
                                                    $semester2Recovery = $recoveryData['average'] ?? null;

                                                    // Calcular média do 2º semestre (média das 4 AVAs)
                                                    $validNotes = array_filter($semester2Notes, function($note) { return $note > 0; });
                                                    if(!empty($validNotes)) {
                                                        $semester2Average = array_sum($validNotes) / count($validNotes);
                                                    }
                                                    // Se houver recuperação e ela for maior que a média, usar a recuperação
                                                    if($semester2Recovery !== null && $semester2Recovery > $semester2Average) {
                                                        $semester2Average = $semester2Recovery;
                                                    }
                                                @endphp

                                                @foreach($semester2Notes as $grade)
                                                    <td class="text-center">{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                                                @endforeach
                                                <td class="text-center">{{ $semester2Recovery ? number_format($semester2Recovery, 1, ',', '.') : '-' }}</td>
                                                <td class="text-center fw-bold">{{ $semester2Average > 0 ? number_format($semester2Average, 1, ',', '.') : '-' }}</td>

                                                <!-- RESULTADO FINAL -->
                                                @php
                                                    $totalPoints = array_sum($semester1Notes) + array_sum($semester2Notes);
                                                    $finalAverage = 0;
                                                    if($semester1Average > 0 && $semester2Average > 0) {
                                                        $finalAverage = ($semester1Average + $semester2Average) / 2;
                                                    } elseif($semester1Average > 0) {
                                                        $finalAverage = $semester1Average;
                                                    } elseif($semester2Average > 0) {
                                                        $finalAverage = $semester2Average;
                                                    }

                                                    $finalExam = $subjectData['periods']['prova_final']['average'] ?? null;
                                                    $overallAverage = $finalAverage;
                                                    if($finalExam) {
                                                        $overallAverage = ($finalAverage * 0.7) + ($finalExam * 0.3);
                                                    }
                                                @endphp

                                                <td class="text-center">{{ $totalPoints > 0 ? number_format($totalPoints, 1, ',', '.') : '-' }}</td>
                                                <td class="text-center fw-bold">{{ $finalAverage > 0 ? number_format($finalAverage, 1, ',', '.') : '-' }}</td>
                                                <td class="text-center">{{ $finalExam ? number_format($finalExam, 1, ',', '.') : '-' }}</td>
                                                <td class="text-center fw-bold text-primary">{{ $overallAverage > 0 ? number_format($overallAverage, 1, ',', '.') : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Legenda -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Legenda de Notas</h6>
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

<style>
@media print {
    .btn, .card-header .d-flex {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .table {
        font-size: 10px !important;
    }

    .table th, .table td {
        padding: 2px !important;
    }
}

.table th {
    background-color: #343a40 !important;
    color: white !important;
    font-weight: bold !important;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6 !important;
}

.text-center {
    text-align: center !important;
}

.fw-bold {
    font-weight: bold !important;
}
</style>
@endsection
