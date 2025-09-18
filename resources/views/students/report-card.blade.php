@extends('layouts.admin')

@section('title', 'Boletim de Avaliações')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->name }}</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Boletim</span>
@endsection

@section('content')
<!-- Versão: 2025-01-27-v2 -->
<div class="container-fluid">
    <!-- Cabeçalho do Boletim -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-0">
                                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="me-3" style="height: 60px;">
                                COLÉGIO PRATICUS
                            </h2>
                            <h4 class="mb-0">COOPERATIVA EDUCACIONAL DE BARRAS - COEB</h4>
                            <p class="mb-0">Rua São José, 149 - Centro - CEP - 64100-000 CNPJ - 05.490.346/0001-39</p>
                            <p class="mb-0">FONE: (86) 3242-2005 / Barras-PI</p>
                            <p class="mb-0"><strong>REFERÊNCIA NA QUALIDADE DO ENSINO</strong></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('students.report-card-pdf', $student) }}?year={{ $schoolYear }}"
                               class="btn btn-success btn-lg">
                                <i class="fas fa-file-pdf me-2"></i>
                                Gerar PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados do Aluno -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><strong>ALUNO:</strong> {{ strtoupper($student->name) }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h5><strong>TURMA:</strong> {{ $student->schoolClass->name ?? 'N/A' }}
                                <strong>TURNO:</strong> {{ $student->schoolClass->shift ?? 'N/A' }}
                                <strong>ANO:</strong> {{ $schoolYear }}
                                <strong>{{ $student->schoolClass->grade ?? 'N/A' }}</strong>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Info -->
    @if(isset($debugInfo))
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Debug:</strong> Carregado em {{ $debugInfo['timestamp'] }} |
                Notas: {{ $debugInfo['notes_count'] }} |
                Disciplinas: {{ $debugInfo['report_data_count'] }} |
                <span class="badge bg-success">Versão Atualizada</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Seleção de Ano Letivo -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-4">
                            <label for="year" class="form-label"><strong>Ano Letivo:</strong></label>
                            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                                @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}" {{ $year == $schoolYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-8">
                            <p class="mb-0 text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Selecione o ano letivo para visualizar o boletim correspondente.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela do Boletim -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="font-size: 12px;">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle" style="width: 200px;">
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
                                @forelse($reportData as $data)
                                    <tr>
                                        <!-- Nome da Disciplina -->
                                        <td class="fw-bold">{{ $data['subject'] }}</td>

                                        <!-- 1º SEMESTRE -->
                                        @foreach($data['semester1']['evaluations'] as $grade)
                                            <td class="text-center">{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                                        @endforeach
                                        <td class="text-center">{{ $data['semester1']['recovery'] ? number_format($data['semester1']['recovery'], 1, ',', '.') : '-' }}</td>
                                        <td class="text-center fw-bold">{{ $data['semester1']['average'] > 0 ? number_format($data['semester1']['average'], 1, ',', '.') : '-' }}</td>

                                        <!-- 2º SEMESTRE -->
                                        @foreach($data['semester2']['evaluations'] as $grade)
                                            <td class="text-center">{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                                        @endforeach
                                        <td class="text-center">{{ $data['semester2']['recovery'] ? number_format($data['semester2']['recovery'], 1, ',', '.') : '-' }}</td>
                                        <td class="text-center fw-bold">{{ $data['semester2']['average'] > 0 ? number_format($data['semester2']['average'], 1, ',', '.') : '-' }}</td>

                                        <!-- RESULTADO FINAL -->
                                        <td class="text-center">{{ ($data['semester1']['points'] + $data['semester2']['points']) > 0 ? number_format($data['semester1']['points'] + $data['semester2']['points'], 1, ',', '.') : '-' }}</td>
                                        <td class="text-center fw-bold">{{ $data['final_average'] > 0 ? number_format($data['final_average'], 1, ',', '.') : '-' }}</td>
                                        <td class="text-center">{{ $data['final_exam'] ? number_format($data['final_exam'], 1, ',', '.') : '-' }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $data['overall_average'] > 0 ? number_format($data['overall_average'], 1, ',', '.') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Nenhuma nota encontrada para este ano letivo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé do Boletim -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border-top pt-3">
                                <p class="mb-0"><strong>ASSINATURA DO RESPONSÁVEL:</strong></p>
                                <div style="height: 50px; border-bottom: 1px solid #000;"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-top pt-3">
                                <p class="mb-0">
                                    O portador deste Boletim foi ________ no(a) ________ Ano/Série,
                                    devendo ser matriculado no(a) ________ Ano/Série do Ensino ________
                                    Barras, ________ de ________ de ________
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('students.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>
                Voltar para Lista
            </a>
            <a href="{{ route('students.show', $student) }}" class="btn btn-info me-2">
                <i class="fas fa-user me-2"></i>
                Ver Perfil do Aluno
            </a>
            <a href="{{ route('students.report-card-pdf', $student) }}?year={{ $schoolYear }}"
               class="btn btn-success">
                <i class="fas fa-file-pdf me-2"></i>
                Baixar PDF
            </a>
        </div>
    </div>
</div>

<style>
/* Cache bust: 2025-01-27-v2 */
.table th, .table td {
    vertical-align: middle;
    padding: 8px 4px;
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: bold;
}

.table-bordered th, .table-bordered td {
    border: 1px solid #dee2e6;
}

.fw-bold {
    font-weight: bold;
}

.text-center {
    text-align: center;
}

/* NOVA ESTRUTURA: AVA -> REC -> MÉDIA */
.semester-structure {
    background-color: #e8f5e8 !important;
}

@media print {
    .btn, .card-header .col-md-4 {
        display: none !important;
    }

    .container-fluid {
        padding: 0;
    }

    .card {
        border: none;
        box-shadow: none;
    }
}
</style>
@endsection
