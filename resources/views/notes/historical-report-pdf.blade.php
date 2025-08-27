<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Histórico Escolar - {{ $student->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            background: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            padding: 15mm 20mm 15mm 10mm;
            box-sizing: border-box;
        }

        /* Cabeçalho */
        .header {
            text-align: center;
            margin: 0 0 20px -2mm;
            border: 2px solid #000;
            padding: 15px;
            width: calc(100% + 2mm);
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .header .school-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .header .school-info {
            font-size: 12px;
            margin-bottom: 4px;
        }

        .header .emission-date {
            font-size: 10px;
            color: #666;
            margin-top: 12px;
        }

        /* Dados Pessoais */
        .personal-data {
            margin: 0 auto 25px auto;
            border: 1px solid #ccc;
            width: 100%;
        }

        .section-header {
            background-color: #f0f0f0;
            padding: 10px 12px;
            font-weight: bold;
            font-size: 13px;
            border-bottom: 1px solid #ccc;
        }

        .section-content {
            padding: 15px;
        }

        .data-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .data-item {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
        }

        .data-label {
            font-weight: bold;
            display: inline-block;
            min-width: 90px;
        }

        /* Resumo Acadêmico */
        .academic-summary {
            margin: 0 0 25px -2mm;
            text-align: center;
            border: 1px solid #000;
            padding: 18px;
            width: calc(100% + 2mm);
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .summary-average {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .summary-stats {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .summary-stat {
            display: table-cell;
            text-align: center;
            padding: 5px;
        }

        .summary-stat-value {
            font-size: 14px;
            font-weight: bold;
        }

        .summary-stat-label {
            font-size: 9px;
            color: #666;
        }

        /* Anos Letivos */
        .year-section {
            margin: 0 auto 30px auto;
            page-break-inside: avoid;
            width: 100%;
        }

        .year-header {
            background-color: #f8f9fa;
            border: 1px solid #000;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .year-stats {
            font-size: 11px;
        }

        .year-badge {
            display: inline-block;
            padding: 3px 8px;
            margin-left: 8px;
            border: 1px solid #000;
            font-size: 10px;
        }

        /* Tabelas */
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px auto 0 auto;
            font-size: 10px;
        }

        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .subjects-table th {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }

        .subjects-table .subject-name {
            font-weight: bold;
        }

        .subjects-table .grade-cell {
            text-align: center;
            font-weight: bold;
        }

        .subjects-table .status-cell {
            text-align: center;
        }

        .subjects-table .periods-cell {
            font-size: 9px;
        }

        .period-badge {
            display: inline-block;
            padding: 2px 4px;
            margin: 1px;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            font-size: 9px;
        }

        /* Resumo do Ano */
        .year-summary {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Timeline */
        .timeline-section {
            margin: 25px auto;
            page-break-inside: avoid;
            width: 100%;
        }

        .timeline-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .timeline-item {
            border-left: 2px solid #ccc;
            padding-left: 15px;
            margin-bottom: 10px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 5px;
            width: 8px;
            height: 8px;
            background-color: #fff;
            border: 2px solid #007bff;
            border-radius: 50%;
        }

        .timeline-year {
            font-weight: bold;
            font-size: 11px;
        }

        .timeline-details {
            font-size: 10px;
            color: #666;
        }

        /* Informações Finais */
        .final-info {
            margin: 25px auto 0 auto;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            font-size: 10px;
            width: 100%;
        }

        .final-info ul {
            padding-left: 15px;
        }

        .final-info li {
            margin-bottom: 3px;
        }

        /* Rodapé */
        .footer {
            margin: 35px auto 0 auto;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 18px;
            font-size: 10px;
            width: 100%;
        }

        .footer .school-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Cores para status */
        .status-aprovado { color: #28a745; }
        .status-reprovado { color: #dc3545; }
        .status-recuperacao { color: #ffc107; }

        .grade-aprovado { color: #28a745; }
        .grade-reprovado { color: #dc3545; }
        .grade-recuperacao { color: #ffc107; }

        /* Page breaks */
        .page-break {
            page-break-before: always;
        }

        .no-page-break {
            page-break-inside: avoid;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container clearfix">
        <!-- Cabeçalho Oficial -->
        <div class="header">
            <h1>Histórico Escolar</h1>
            <div class="school-name">{{ strtoupper($student->school->name) }}</div>
            <div class="school-info">{{ $student->school->full_address }}</div>
            @if($student->school->cnpj)
                <div class="school-info">CNPJ: {{ $student->school->formatted_cnpj }}</div>
            @endif
            <div class="emission-date">Documento emitido em {{ now()->format('d/m/Y') }}</div>
        </div>

        <!-- Dados Pessoais -->
        <div class="personal-data">
            <div class="section-header">DADOS PESSOAIS DO ALUNO</div>
            <div class="section-content">
                <div class="data-row">
                    <div class="data-item">
                        <span class="data-label">Nome:</span> {{ $student->name }}
                    </div>
                    <div class="data-item">
                        <span class="data-label">Matrícula:</span> {{ $student->enrollment }}
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-item">
                        <span class="data-label">CPF:</span> {{ $student->formatted_cpf ?: 'Não informado' }}
                    </div>
                    <div class="data-item">
                        <span class="data-label">RG:</span> {{ $student->rg ?: 'Não informado' }}
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-item">
                        <span class="data-label">Nascimento:</span> {{ $student->birth_date->format('d/m/Y') }}
                    </div>
                    <div class="data-item">
                        <span class="data-label">Naturalidade:</span> {{ $student->city }}/{{ $student->state }}
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-item">
                        <span class="data-label">Nacionalidade:</span> {{ $student->country }}
                    </div>
                    <div class="data-item">
                        <span class="data-label">Filiação:</span> {{ $student->guardian_name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo Acadêmico Geral -->
        <div class="academic-summary">
            <div class="summary-title">RESUMO ACADÊMICO GERAL</div>
            <div class="summary-average grade-{{ $overallStats['overall_average'] >= 6 ? 'aprovado' : ($overallStats['overall_average'] >= 4 ? 'recuperacao' : 'reprovado') }}">
                {{ number_format($overallStats['overall_average'], 1, ',', '.') }}
            </div>
            <div>Média Geral</div>
            <div class="summary-stats">
                <div class="summary-stat">
                    <div class="summary-stat-value">{{ $overallStats['total_approved'] }}</div>
                    <div class="summary-stat-label">Disciplinas Aprovadas</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-stat-value">{{ $overallStats['total_years'] }}</div>
                    <div class="summary-stat-label">Anos Cursados</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-stat-value">{{ $overallStats['total_failed'] }}</div>
                    <div class="summary-stat-label">Disciplinas Reprovadas</div>
                </div>
            </div>
        </div>

        <!-- Histórico por Ano Letivo -->
        @foreach($historicalData as $yearData)
            <div class="year-section no-page-break">
                <div class="year-header">
                    <div>Ano Letivo: {{ $yearData['year'] }}</div>
                    <div class="year-stats">
                        <span class="year-badge">Média: {{ number_format($yearData['general_average'], 1, ',', '.') }}</span>
                        <span class="year-badge">{{ ucfirst($yearData['academic_status']) }}</span>
                    </div>
                </div>

                @if(count($yearData['subjects']) > 0)
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Disciplina</th>
                                <th style="width: 12%;">Média Final</th>
                                <th style="width: 12%;">Situação</th>
                                <th style="width: 12%;">Carga Horária</th>
                                <th style="width: 34%;">Detalhes por Período</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearData['subjects'] as $subjectKey => $subjectData)
                                <tr>
                                    <td class="subject-name">{{ $subjectData['name'] }}</td>
                                    <td class="grade-cell grade-{{ $subjectData['average'] >= 6 ? 'aprovado' : ($subjectData['average'] >= 4 ? 'recuperacao' : 'reprovado') }}">
                                        {{ $subjectData['average'] ? number_format($subjectData['average'], 1, ',', '.') : '-' }}
                                    </td>
                                    <td class="status-cell status-{{ $subjectData['status'] }}">
                                        {{ ucfirst($subjectData['status']) }}
                                    </td>
                                    <td class="grade-cell">{{ $subjectData['notes_count'] * 20 }}h</td>
                                    <td class="periods-cell">
                                        @if(count($subjectData['periods']) > 0)
                                            @foreach($subjectData['periods'] as $periodKey => $periodData)
                                                <span class="period-badge">
                                                    {{ $periodData['name'] }}: {{ $periodData['average'] ? number_format($periodData['average'], 1, ',', '.') : '-' }}
                                                </span>
                                            @endforeach
                                        @else
                                            Sem períodos registrados
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="year-summary">
                                <td>RESUMO DO ANO</td>
                                <td class="grade-cell grade-{{ $yearData['general_average'] >= 6 ? 'aprovado' : ($yearData['general_average'] >= 4 ? 'recuperacao' : 'reprovado') }}">
                                    {{ number_format($yearData['general_average'], 1, ',', '.') }}
                                </td>
                                <td class="status-cell status-{{ $yearData['academic_status'] }}">
                                    {{ ucfirst($yearData['academic_status']) }}
                                </td>
                                <td class="grade-cell">{{ $yearData['total_subjects'] * 160 }}h</td>
                                <td class="periods-cell">
                                    {{ $yearData['approved_subjects'] }} aprovadas | {{ $yearData['failed_subjects'] }} reprovadas
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p style="padding: 20px; text-align: center; color: #666;">
                        Nenhuma disciplina registrada para o ano letivo de {{ $yearData['year'] }}.
                    </p>
                @endif
            </div>
        @endforeach

        <!-- Timeline Acadêmica -->
        <div class="timeline-section">
            <div class="timeline-title">TIMELINE ACADÊMICA</div>
            @foreach($academicSummary as $summary)
                <div class="timeline-item">
                    <div class="timeline-year">Ano Letivo {{ $summary['year'] }}</div>
                    <div class="timeline-details">
                        {{ $summary['subjects_count'] }} disciplinas |
                        Média: {{ number_format($summary['average'], 1, ',', '.') }} |
                        Status: {{ ucfirst($summary['status']) }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Informações Complementares -->
        <div class="final-info">
            <strong>Informações Complementares:</strong>
            <ul>
                <li>Este histórico escolar contém {{ $overallStats['total_subjects_studied'] }} registros de disciplinas cursadas</li>
                <li>Período de estudos: {{ min($schoolYears) }} a {{ max($schoolYears) }}</li>
                <li>Status atual do aluno: <strong>{{ ucfirst($overallStats['current_status']) }}</strong></li>
                <li>Histórico gerado em: {{ now()->format('d/m/Y às H:i') }}</li>
                <li>Sistema de avaliação: Aprovado ≥6,0 | Recuperação 4,0-5,9 | Reprovado <4,0</li>
            </ul>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <div class="school-name">{{ $student->school->name }}</div>
            <div>{{ $student->school->full_address }}</div>
            <div>Este documento foi gerado eletronicamente em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}</div>
        </div>
    </div>
</body>
</html>
