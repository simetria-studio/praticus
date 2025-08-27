<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado</title>
    <style>
        @page {
            size: A4-L;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
        }

        .certificate-container {
            border: 3mm solid #1e40af;
            padding: 8mm;
            background: #fff;
        }

        .inner-border {
            border: 1mm solid #1e40af;
            padding: 6mm;
            text-align: center;
        }

        h1 {
            color: #1e40af;
            font-size: 12px;
            margin: 2mm 0;
            font-weight: bold;
        }

        h2 {
            color: #1e40af;
            font-size: 14px;
            margin: 4mm 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .school-info {
            font-size: 9px;
            margin: 2mm 0;
        }

        .content {
            font-size: 10px;
            text-align: justify;
            margin: 4mm 0;
            line-height: 1.4;
        }

        .student-name {
            font-weight: bold;
            text-decoration: underline;
            color: #1e40af;
        }

        .signatures {
            margin-top: 8mm;
            width: 100%;
        }

        .verification {
            font-size: 7px;
            color: #666;
            margin-top: 4mm;
            text-align: right;
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
                <strong>CNPJ N° {{ $school_cnpj ?? '08.055.298/0001-49' }} - INEP N° {{ $school_inep ?? '22136703' }}</strong>
            </div>

            <div class="school-info">
                <strong>{{ $school_name ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE' }}</strong><br>
                {{ $school_address ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI' }}
            </div>

            <div class="school-info">
                Autorização de Funcionamento pela resolução CEE/PI N°__ {{ $authorization ?? '224/2022' }} de {{ $authorization_date ?? '02/12/2022' }}
            </div>

            <h2>CERTIFICADO DE CONCLUSÃO DO ENSINO {{ $course_level ?? 'MÉDIO' }}</h2>

            <div class="content">
                A Direção do Centro Estadual de Tempo Integral Francisca Trindade, no uso de suas atribuições legais, confere a <span class="student-name">{{ $student_name ?? 'ALUNO EXEMPLO DA SILVA' }}</span>, CPF {{ $student_cpf ?? '000.000.000-00' }}, nascido em {{ $student_birth_day ?? '30' }} de {{ $student_birth_month ?? 'JULHO' }} de {{ $student_birth_year ?? '2006' }}, natural de {{ $student_birthplace ?? 'BARRAS' }}, Estado do Piauí, nacionalidade {{ $student_nationality ?? 'BRASILEIRA' }}, filho de {{ $student_father ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO' }} e de {{ $student_mother ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA' }}, o presente certificado por ter concluído no ano {{ $completion_year ?? '2023' }} o Ensino {{ $course_level ?? 'MÉDIO' }}, para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.
            </div>

            <div class="school-info">
                <strong>{{ $issue_location ?? 'BARRAS' }} - {{ $issue_state ?? 'PI' }}, {{ $issue_day ?? '08' }} de {{ $issue_month ?? 'FEVEREIRO' }} de {{ $issue_year ?? '2024' }}.</strong>
            </div>

            <div class="signatures">
                <table style="width: 100%; border-collapse: collapse; margin-top: 6mm;">
                    <tr>
                        <td style="width: 33%; text-align: center; padding: 0 8px;">
                            <div style="border-bottom: 1px solid black; height: 6mm; margin-bottom: 2mm;"></div>
                            <div style="font-size: 8px; font-weight: bold;">SECRETÁRIO(A)</div>
                        </td>
                        <td style="width: 33%; text-align: center; padding: 0 8px;">
                            <div style="border-bottom: 1px solid black; height: 6mm; margin-bottom: 2mm;"></div>
                            <div style="font-size: 8px; font-weight: bold;">DIRETOR(A)</div>
                        </td>
                        <td style="width: 34%; text-align: center; padding: 0 8px;">
                            <div style="border-bottom: 1px solid black; height: 6mm; margin-bottom: 2mm;"></div>
                            <div style="font-size: 8px; font-weight: bold;">CONCLUINTE</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="verification">
                Código: {{ $verification_code ?? 'CERT-' . date('Y') . '-12345' }}
            </div>

        </div>
    </div>
</body>
</html>
