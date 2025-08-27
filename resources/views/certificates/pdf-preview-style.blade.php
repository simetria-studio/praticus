<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado</title>
    <style>
        @page {
            size: A4-L;
            margin: 15mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
        }

        .certificate-container {
            border: 4mm solid #1e40af;
            padding: 10mm;
            background: white;
            text-align: center;
        }

        .inner-border {
            border: 1mm solid #1e40af;
            padding: 8mm;
            min-height: 140mm;
        }

        h1 {
            color: #1e40af;
            font-size: 12px;
            font-weight: bold;
            margin: 3mm 0;
        }

        h2 {
            color: #1e40af;
            font-size: 16px;
            font-weight: bold;
            margin: 8mm 0;
            text-transform: uppercase;
        }

        .school-info {
            font-size: 9px;
            margin: 4mm 0;
            font-weight: bold;
        }

        .content {
            font-size: 10px;
            text-align: justify;
            margin: 6mm 0;
            line-height: 1.4;
        }

        .student-name {
            font-weight: bold;
            text-decoration: underline;
            color: #1e40af;
        }

        .location-date {
            font-size: 10px;
            margin: 6mm 0;
            font-weight: bold;
        }

        .signatures-table {
            width: 100%;
            margin-top: 15mm;
            border-collapse: collapse;
        }

        .signatures-table td {
            width: 33.33%;
            text-align: center;
            padding: 0 5mm;
            vertical-align: top;
        }

        .signature-line {
            border-bottom: 1px solid black;
            height: 8mm;
            margin-bottom: 3mm;
        }

        .signature-title {
            font-size: 8px;
            font-weight: bold;
        }

        .verification {
            font-size: 7px;
            color: #666;
            text-align: right;
            margin-top: 5mm;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="inner-border">

            <h1>REPÚBLICA FEDERATIVA DO BRASIL</h1>
            <h1>ESTADO DO PIAUÍ</h1>
            <h1>SECRETARIA DE ESTADO DA EDUCAÇÃO</h1>

            <div class="school-info">
                CNPJ N° {{ $school_cnpj ?? '08.055.298/0001-49' }} - INEP N° {{ $school_inep ?? '22136703' }}
            </div>

            <div class="school-info">
                {{ $school_name ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE' }}<br>
                {{ $school_address ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI' }}
            </div>

            <div class="school-info">
                Autorização de Funcionamento pela resolução CEE/PI N°__ {{ $authorization ?? '224/2022' }} de {{ $authorization_date ?? '02/12/2022' }}
            </div>

            <h2>CERTIFICADO DE CONCLUSÃO DO ENSINO {{ $course_level ?? 'MÉDIO' }}</h2>

            <div class="content">
                A Direção do {{ $school_type ?? 'Centro Estadual de Tempo Integral - CETI' }} <strong>{{ $school_short_name ?? 'FRANCISCA TRINDADE' }}</strong> no uso de suas atribuições legais confere a <span class="student-name">{{ $student_name ?? 'ALUNO EXEMPLO DA SILVA' }}</span>, CPF {{ $student_cpf ?? '000.000.000-00' }}, nascido (a) em {{ $student_birth_day ?? '30' }} de {{ $student_birth_month ?? 'JULHO' }} de {{ $student_birth_year ?? '2006' }}, natural de {{ $student_birthplace ?? 'BARRAS' }}, Estado de (o) {{ $student_birth_state ?? 'PIAUÍ' }}, nacionalidade {{ $student_nationality ?? 'BRASILEIRA' }}, filho (a) de {{ $student_father ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO' }} e de {{ $student_mother ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA' }}, o presente certificado por ter concluído no ano {{ $completion_year ?? '2023' }} o Ensino {{ $course_level ?? 'MÉDIO' }}, para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.
            </div>

            <div class="location-date">
                {{ $issue_location ?? 'BARRAS' }} - {{ $issue_state ?? 'PI' }}, {{ $issue_day ?? '08' }} de {{ $issue_month ?? 'FEVEREIRO' }} de {{ $issue_year ?? '2024' }}.
            </div>

            <table class="signatures-table">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-title">SECRETÁRIO(A)</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-title">DIRETOR(A)</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-title">CONCLUINTE</div>
                    </td>
                </tr>
            </table>

            <div class="verification">
                Código de verificação: {{ $verification_code ?? 'CERT-' . date('Y') . '-12345' }}
            </div>

        </div>
    </div>
</body>
</html>
