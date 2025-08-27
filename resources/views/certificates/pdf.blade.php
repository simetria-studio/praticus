<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Conclusão</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10mm;
        }

        /* Container principal - pode usar flexbox com Browsershot */
        .certificate-content {
            position: relative;
            width: 277mm;
            height: 190mm;
            display: inline-block;
            background: white;
        }

        /* Borda principal */
        .certificate-border {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 12mm solid #1e40af;
            box-sizing: border-box;
            background: white;
        }

        .certificate-border::before {
            content: '';
            position: absolute;
            top: 6mm;
            left: 6mm;
            right: 6mm;
            bottom: 6mm;
            border: 2mm solid #1e40af;
        }

        /* Círculos decorativos nos cantos */
        .decorative-circles {
            position: absolute;
            width: 6mm;
            height: 6mm;
            background: #1e40af;
            border-radius: 50%;
            z-index: 2;
        }

        .circle-top-left { top: 18mm; left: 18mm; }
        .circle-top-right { top: 18mm; right: 18mm; }
        .circle-bottom-left { bottom: 18mm; left: 18mm; }
        .circle-bottom-right { bottom: 18mm; right: 18mm; }

        /* Padrões decorativos horizontais */
        .decorative-pattern {
            position: absolute;
            top: 22mm;
            left: 36mm;
            right: 36mm;
            height: 7mm;
            background: linear-gradient(90deg,
                #1e40af 0%, transparent 20%, #1e40af 40%, transparent 60%, #1e40af 80%, transparent 100%);
            opacity: 0.3;
            z-index: 2;
        }

        .decorative-pattern-bottom {
            position: absolute;
            bottom: 22mm;
            left: 36mm;
            right: 36mm;
            height: 7mm;
            background: linear-gradient(90deg,
                #1e40af 0%, transparent 20%, #1e40af 40%, transparent 60%, #1e40af 80%, transparent 100%);
            opacity: 0.3;
            z-index: 2;
        }

        /* Brasão do Brasil */
        .brasao {
            position: absolute;
            top: 35mm;
            left: 50%;
            transform: translateX(-50%);
            width: 12mm;
            height: 12mm;
            background: radial-gradient(circle, #1e40af 0%, #1d4ed8 50%, #1e3a8a 100%);
            border-radius: 50%;
            border: 1mm solid #ffffff;
            box-shadow: 0 1mm 3mm rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.6mm;
            text-align: center;
            line-height: 1.1;
            z-index: 3;
        }

        /* Conteúdo principal - usando flexbox moderno */
        .cert-content {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 40mm 45mm 30mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 3;
        }

        /* Cabeçalho */
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }

        .header h1 {
            font-size: 3mm;
            font-weight: bold;
            margin: 0.5mm 0;
            color: #1e40af;
            line-height: 1.2;
        }

        /* Informações da escola */
        .school-info {
            text-align: center;
            margin-bottom: 2.5mm;
            font-size: 2.4mm;
            line-height: 1.3;
        }

        /* CNPJ e INEP */
        .cnpj-inep {
            text-align: center;
            margin-bottom: 1.5mm;
            font-size: 2.4mm;
            font-weight: bold;
        }

        /* Autorização */
        .authorization {
            text-align: center;
            margin-bottom: 3mm;
            font-size: 2mm;
        }

        /* Título do certificado */
        .title {
            text-align: center;
            margin: 3mm 0 4mm;
            border-bottom: 0.8mm solid #1e40af;
            padding-bottom: 1.5mm;
        }

        .title h1 {
            font-size: 4mm;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.2mm;
            margin: 0;
        }

        /* Conteúdo principal - flexível */
        .content {
            text-align: justify;
            margin: 3mm 0;
            line-height: 1.3;
            font-size: 2.6mm;
            flex: 1;
            display: flex;
            align-items: center;
        }

        .content p {
            margin: 0;
            text-indent: 5mm;
        }

        /* Nome do estudante */
        .student-name {
            font-weight: bold;
            color: #1e40af;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* Informações do curso */
        .course-info {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Local e data */
        .location-date {
            text-align: center;
            margin: 4mm 0 3mm;
            font-size: 2.6mm;
            font-weight: bold;
        }

        /* Seção de assinaturas - usando flexbox */
        .signatures {
            margin-top: 4mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signature {
            text-align: center;
            flex: 1;
            margin: 0 2mm;
        }

        .signature-line {
            border-bottom: 0.3mm solid #000;
            width: 30mm;
            margin: 0 auto 1.5mm;
            height: 6mm;
        }

        .signature-title {
            font-size: 2mm;
            font-weight: bold;
        }

        /* Código de verificação */
        .verification-code {
            position: absolute;
            bottom: 10mm;
            right: 18mm;
            font-size: 1.6mm;
            color: #666;
            z-index: 4;
        }
    </style>
</head>
<body>
    <div class="certificate-content">
        <!-- Borda decorativa do certificado -->
        <div class="certificate-border"></div>

        <!-- Círculos decorativos nos cantos -->
        <div class="decorative-circles circle-top-left"></div>
        <div class="decorative-circles circle-top-right"></div>
        <div class="decorative-circles circle-bottom-left"></div>
        <div class="decorative-circles circle-bottom-right"></div>

        <!-- Padrões decorativos -->
        <div class="decorative-pattern"></div>
        <div class="decorative-pattern-bottom"></div>

        <!-- Brasão do Brasil -->
        <div class="brasao">
            BRASÃO<br>DO<br>BRASIL
        </div>

        <div class="cert-content">
            <!-- Cabeçalho oficial -->
            <div class="header">
                <h1>REPÚBLICA FEDERATIVA DO BRASIL</h1>
                <h1>ESTADO DO PIAUÍ</h1>
                <h1>SECRETARIA DE ESTADO DA EDUCAÇÃO</h1>
            </div>

            <!-- Informações CNPJ e INEP -->
            <div class="cnpj-inep">
                CNPJ N° {{ $school_cnpj ?? '08.055.298/0001-49' }} &nbsp;&nbsp;&nbsp;&nbsp; INEP N° {{ $school_inep ?? '22136703' }}
            </div>

            <!-- Nome da escola -->
            <div class="school-info">
                <strong>{{ $school_name ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE' }}</strong><br>
                <span style="font-size: 2mm;">NOME DO ESTABELECIMENTO DE ENSINO</span><br>
                <strong>{{ $school_address ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI' }}</strong><br>
                <span style="font-size: 2mm;">ENDEREÇO</span>
            </div>

            <!-- Autorização -->
            <div class="authorization">
                Autorização de Funcionamento pela resolução CEE/PI N°__ {{ $authorization ?? '224/2022' }} de {{ $authorization_date ?? '02/12/2022' }}
            </div>

            <!-- Título do certificado -->
            <div class="title">
                <h1>CERTIFICADO DE CONCLUSÃO DO ENSINO <span class="course-info">{{ $course_level ?? 'MÉDIO' }}</span></h1>
            </div>

            <!-- Conteúdo principal -->
            <div class="content">
                <p>A Direção do {{ $school_type ?? 'Centro Estadual de Tempo Integral - CETI' }} <strong>{{ $school_short_name ?? 'FRANCISCA TRINDADE' }}</strong> no uso de suas atribuições legais confere a <span class="student-name">{{ $student_name ?? 'ALUNO EXEMPLO DA SILVA' }}</span>, CPF {{ $student_cpf ?? '000.000.000-00' }}, nascido (a) em {{ $student_birth_day ?? '30' }} de {{ $student_birth_month ?? 'JULHO' }} de {{ $student_birth_year ?? '2006' }}, natural de {{ $student_birthplace ?? 'BARRAS' }}, Estado de (o) {{ $student_birth_state ?? 'PIAUÍ' }}, nacionalidade {{ $student_nationality ?? 'BRASILEIRA' }}, filho (a) de {{ $student_father ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO' }} e de {{ $student_mother ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA' }}, o presente certificado por ter concluído no ano {{ $completion_year ?? '2023' }} o Ensino <span class="course-info">{{ $course_level ?? 'MÉDIO' }}</span>, para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.</p>
            </div>

            <!-- Local e data -->
            <div class="location-date">
                {{ $issue_location ?? 'BARRAS' }} - {{ $issue_state ?? 'PI' }}, {{ $issue_day ?? '08' }} de {{ $issue_month ?? 'FEVEREIRO' }} de {{ $issue_year ?? '2024' }}.
            </div>

            <!-- Assinaturas -->
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">SECRETÁRIO(A)</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">DIRETOR(A)</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">CONCLUINTE</div>
                </div>
            </div>

            <!-- Código de verificação -->
            <div class="verification-code">
                Código de verificação: {{ $verification_code ?? 'CERT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>
</body>
</html>
