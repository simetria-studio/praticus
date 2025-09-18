<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletim de Avaliações - {{ $student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo {
            height: 50px;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }

        .school-info {
            font-size: 10px;
            margin: 2px 0;
        }

        .student-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }

        .subject-column {
            text-align: left;
            font-weight: bold;
            width: 120px;
        }

        .semester-header {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .evaluation-header {
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .signature-section {
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 30px;
            margin-top: 5px;
        }

        .text-section {
            display: inline-block;
            width: 50%;
            vertical-align: top;
            margin-left: 5%;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .no-border {
            border: none;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <div class="school-name">COLÉGIO PRATICUS</div>
        <div class="school-info">COOPERATIVA EDUCACIONAL DE BARRAS - COEB</div>
        <div class="school-info">Rua São José, 149 - Centro - CEP - 64100-000 CNPJ - 05.490.346/0001-39</div>
        <div class="school-info">FONE: (86) 3242-2005 / Barras-PI</div>
        <div class="school-info bold">REFERÊNCIA NA QUALIDADE DO ENSINO</div>
    </div>

    <!-- Dados do Aluno -->
    <div class="student-info">
        <div><strong>ALUNO:</strong> {{ strtoupper($student->name) }}</div>
        <div><strong>TURMA:</strong> {{ $student->schoolClass->name ?? 'N/A' }}
            <strong>TURNO:</strong> {{ $student->schoolClass->shift ?? 'N/A' }}
            <strong>ANO:</strong> {{ $schoolYear }}
            <strong>{{ $student->schoolClass->grade ?? 'N/A' }}</strong>
        </div>
    </div>

    <!-- Tabela do Boletim -->
    <table class="report-table">
        <thead>
            <tr>
                <th rowspan="3" class="subject-column">COMPONENTES CURRICULARES</th>

                <!-- 1º SEMESTRE -->
                <th colspan="6" class="semester-header">BOLETIM DE AVALIAÇÕES - 1º SEMESTRE</th>

                <!-- 2º SEMESTRE -->
                <th colspan="6" class="semester-header">BOLETIM DE AVALIAÇÕES - 2º SEMESTRE</th>

                <!-- RESULTADO FINAL -->
                <th colspan="4" class="semester-header">RESULTADO FINAL</th>
            </tr>
            <tr>
                <!-- 1º SEMESTRE -->
                <th colspan="4" class="evaluation-header">NOTA DAS AVALIAÇÕES</th>
                <th class="evaluation-header">REC.</th>
                <th class="evaluation-header">MÉDIA 1º SEME.</th>

                <!-- 2º SEMESTRE -->
                <th colspan="4" class="evaluation-header">NOTA DAS AVALIAÇÕES</th>
                <th class="evaluation-header">REC.</th>
                <th class="evaluation-header">MÉDIA 2º SEME.</th>

                <!-- RESULTADO FINAL -->
                <th class="evaluation-header">PONTOS 1º E 2º SEME.</th>
                <th class="evaluation-header">MÉDIA 1º E 2º SEME.</th>
                <th class="evaluation-header">PROVA FINAL</th>
                <th class="evaluation-header">MÉDIA GERAL</th>
            </tr>
            <tr>
                <!-- 1º SEMESTRE -->
                <th class="evaluation-header">1ª</th>
                <th class="evaluation-header">2ª</th>
                <th class="evaluation-header">3ª</th>
                <th class="evaluation-header">4ª</th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>

                <!-- 2º SEMESTRE -->
                <th class="evaluation-header">5ª</th>
                <th class="evaluation-header">6ª</th>
                <th class="evaluation-header">7ª</th>
                <th class="evaluation-header">8ª</th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>

                <!-- RESULTADO FINAL -->
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>
                <th class="evaluation-header"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $data)
                <tr>
                    <!-- Nome da Disciplina -->
                    <td class="subject-column">{{ $data['subject'] }}</td>

                    <!-- 1º SEMESTRE -->
                    @foreach($data['semester1']['evaluations'] as $grade)
                        <td>{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                    @endforeach
                    <td>{{ $data['semester1']['recovery'] ? number_format($data['semester1']['recovery'], 1, ',', '.') : '-' }}</td>
                    <td class="bold">{{ $data['semester1']['average'] > 0 ? number_format($data['semester1']['average'], 1, ',', '.') : '-' }}</td>

                    <!-- 2º SEMESTRE -->
                    @foreach($data['semester2']['evaluations'] as $grade)
                        <td>{{ $grade > 0 ? number_format($grade, 1, ',', '.') : '-' }}</td>
                    @endforeach
                    <td>{{ $data['semester2']['recovery'] ? number_format($data['semester2']['recovery'], 1, ',', '.') : '-' }}</td>
                    <td class="bold">{{ $data['semester2']['average'] > 0 ? number_format($data['semester2']['average'], 1, ',', '.') : '-' }}</td>

                    <!-- RESULTADO FINAL -->
                    <td>{{ ($data['semester1']['points'] + $data['semester2']['points']) > 0 ? number_format($data['semester1']['points'] + $data['semester2']['points'], 1, ',', '.') : '-' }}</td>
                    <td class="bold">{{ $data['final_average'] > 0 ? number_format($data['final_average'], 1, ',', '.') : '-' }}</td>
                    <td>{{ $data['final_exam'] ? number_format($data['final_exam'], 1, ',', '.') : '-' }}</td>
                    <td class="bold">{{ $data['overall_average'] > 0 ? number_format($data['overall_average'], 1, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="center">Nenhuma nota encontrada para este ano letivo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Rodapé -->
    <div class="footer">
        <div class="signature-section">
            <div class="bold">ASSINATURA DO RESPONSÁVEL:</div>
            <div class="signature-line"></div>
        </div>

        <div class="text-section">
            <div>
                O portador deste Boletim foi ________ no(a) ________ Ano/Série,
                devendo ser matriculado no(a) ________ Ano/Série do Ensino ________
                Barras, ________ de ________ de ________
            </div>
        </div>
    </div>
</body>
</html>
