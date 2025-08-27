@extends('layouts.admin')

@section('title', 'Prévia do Certificado')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Certificados</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Prévia</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-eye me-2"></i>
                            Preview do Certificado - {{ $certificate->certificate_number }}
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('certificates.download', $certificate) }}"
                               class="btn btn-success btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Baixar PDF
                            </a>
                            <a href="{{ route('certificates.show', $certificate) }}"
                               class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Controles de Zoom -->
                    <div class="d-flex justify-content-center align-items-center p-3 bg-light border-bottom">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="zoomOut()">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetZoom()">
                                <span id="zoom-level">75%</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="zoomIn()">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">Use os botões ou Ctrl + (+/-) para ajustar o zoom</small>
                        </div>
                    </div>

                    <!-- Preview do Certificado -->
                    <div class="preview-container d-flex justify-content-center align-items-center" style="background-color: #f8f9fa; min-height: 80vh; padding: 20px;">
                        <div id="certificate-preview" class="certificate-preview" style="transform: scale(0.75); transform-origin: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">

                            <!-- Conteúdo do Certificado -->
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
                                        CNPJ N° {{ $data['school_cnpj'] ?? '08.055.298/0001-49' }} &nbsp;&nbsp;&nbsp;&nbsp; INEP N° {{ $data['school_inep'] ?? '22136703' }}
                                    </div>

                                    <!-- Nome da escola -->
                                    <div class="school-info">
                                        <strong>{{ $data['school_name'] ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE' }}</strong><br>
                                        <span style="font-size: 11px;">NOME DO ESTABELECIMENTO DE ENSINO</span><br>
                                        <strong>{{ $data['school_address'] ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI' }}</strong><br>
                                        <span style="font-size: 11px;">ENDEREÇO</span>
                                    </div>

                                    <!-- Autorização -->
                                    <div class="authorization">
                                        Autorização de Funcionamento pela resolução CEE/PI N°__ {{ $data['authorization'] ?? '224/2022' }} de {{ $data['authorization_date'] ?? '02/12/2022' }}
                                    </div>

                                    <!-- Título do certificado -->
                                    <div class="title">
                                        <h1>CERTIFICADO DE CONCLUSÃO DO ENSINO <span class="course-info">{{ $data['course_level'] ?? 'MÉDIO' }}</span></h1>
                                    </div>

                                    <!-- Conteúdo principal -->
                                    <div class="content">
                                        <p>A Direção do {{ $data['school_type'] ?? 'Centro Estadual de Tempo Integral - CETI' }} <strong>{{ $data['school_short_name'] ?? 'FRANCISCA TRINDADE' }}</strong> no uso de suas atribuições legais confere a <span class="student-name">{{ $data['student_name'] ?? 'ALUNO EXEMPLO DA SILVA' }}</span>, CPF {{ $data['student_cpf'] ?? '000.000.000-00' }}, nascido (a) em {{ $data['student_birth_day'] ?? '30' }} de {{ $data['student_birth_month'] ?? 'JULHO' }} de {{ $data['student_birth_year'] ?? '2006' }}, natural de {{ $data['student_birthplace'] ?? 'BARRAS' }}, Estado de (o) {{ $data['student_birth_state'] ?? 'PIAUÍ' }}, nacionalidade {{ $data['student_nationality'] ?? 'BRASILEIRA' }}, filho (a) de {{ $data['student_father'] ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO' }} e de {{ $data['student_mother'] ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA' }}, o presente certificado por ter concluído no ano {{ $data['completion_year'] ?? '2023' }} o Ensino <span class="course-info">{{ $data['course_level'] ?? 'MÉDIO' }}</span>, para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.</p>
                                    </div>

                                    <!-- Local e data -->
                                    <div class="location-date">
                                        {{ $data['issue_location'] ?? 'BARRAS' }} - {{ $data['issue_state'] ?? 'PI' }}, {{ $data['issue_day'] ?? '08' }} de {{ $data['issue_month'] ?? 'FEVEREIRO' }} de {{ $data['issue_year'] ?? '2024' }}.
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
                                        Código de verificação: {{ $data['verification_code'] ?? 'CERT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Esta é uma visualização do certificado. O PDF final pode ter pequenas diferenças de formatação.
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('certificates.edit', $certificate) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    Editar
                                </a>
                                <a href="{{ route('certificates.download', $certificate) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Baixar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .certificate-preview {
        transition: transform 0.3s ease;
        background: white;
        width: 840px;
        height: 594px;
        max-width: 100%;
        max-height: 100%;
        position: relative;
        overflow: hidden;
    }

    .certificate-content {
        position: relative;
        width: 840px;
        height: 594px;
        font-family: Arial, sans-serif;
        background: white;
        overflow: hidden;
    }

    .certificate-border {
        position: absolute;
        top: 0;
        left: 0;
        width: 840px;
        height: 594px;
        z-index: 1;
        background: white;
        border: 40px solid #1e40af;
        box-sizing: border-box;
    }

    .certificate-border::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 8px solid #1e40af;
        z-index: 1;
    }

    .decorative-circles {
        position: absolute;
        width: 20px;
        height: 20px;
        background: #1e40af;
        border-radius: 50%;
        z-index: 2;
    }

    .circle-top-left { top: 60px; left: 60px; }
    .circle-top-right { top: 60px; right: 60px; }
    .circle-bottom-left { bottom: 60px; left: 60px; }
    .circle-bottom-right { bottom: 60px; right: 60px; }

    .decorative-pattern {
        position: absolute;
        top: 70px;
        left: 120px;
        right: 120px;
        height: 25px;
        background: linear-gradient(90deg,
            #1e40af 0%, transparent 20%, #1e40af 40%, transparent 60%, #1e40af 80%, transparent 100%);
        z-index: 2;
        opacity: 0.3;
    }

    .decorative-pattern-bottom {
        position: absolute;
        bottom: 70px;
        left: 120px;
        right: 120px;
        height: 25px;
        background: linear-gradient(90deg,
            #1e40af 0%, transparent 20%, #1e40af 40%, transparent 60%, #1e40af 80%, transparent 100%);
        z-index: 2;
        opacity: 0.3;
    }

    .cert-content {
        position: relative;
        z-index: 3;
        width: 840px;
        height: 594px;
        padding: 120px 160px 100px;
        box-sizing: border-box;
        color: #000;
    }

    .brasao {
        position: absolute;
        top: 120px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 40px;
        background: radial-gradient(circle, #1e40af 0%, #1d4ed8 50%, #1e3a8a 100%);
        border-radius: 50%;
        z-index: 3;
        border: 4px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 5px;
        text-align: center;
        line-height: 1.1;
    }

    .header {
        text-align: center;
        margin-bottom: 12px;
        margin-top: 50px;
    }

    .header h1 {
        font-size: 9px;
        font-weight: bold;
        margin: 2px 0;
        color: #1e40af;
        line-height: 1.2;
    }

    .school-info {
        text-align: center;
        margin-bottom: 8px;
        font-size: 7px;
        line-height: 1.2;
    }

    .cnpj-inep {
        text-align: center;
        margin-bottom: 6px;
        font-size: 7px;
        font-weight: bold;
    }

    .authorization {
        text-align: center;
        margin-bottom: 12px;
        font-size: 6px;
    }

    .title {
        text-align: center;
        margin: 12px 0 16px;
        border-bottom: 2px solid #1e40af;
        padding-bottom: 6px;
    }

    .title h1 {
        font-size: 12px;
        font-weight: bold;
        color: #1e40af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .content {
        text-align: justify;
        margin: 12px 0;
        line-height: 1.3;
        font-size: 8px;
        text-indent: 0;
    }

    .content p {
        margin: 0;
        text-indent: 15px;
    }

    .student-name {
        font-weight: bold;
        color: #1e40af;
        text-decoration: underline;
        text-transform: uppercase;
    }

    .course-info {
        font-weight: bold;
        text-transform: uppercase;
    }

    .location-date {
        text-align: center;
        margin: 20px 0 12px;
        font-size: 8px;
        font-weight: bold;
    }

    .signatures {
        margin-top: 20px;
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .signature {
        display: table-cell;
        text-align: center;
        padding: 0 10px;
        vertical-align: top;
        width: 33.33%;
    }

    .signature-line {
        border-bottom: 1px solid #000;
        width: 100px;
        margin: 0 auto 5px;
        height: 25px;
    }

    .signature-title {
        font-size: 6px;
        font-weight: bold;
    }

    .verification-code {
        position: absolute;
        bottom: 35px;
        right: 60px;
        font-size: 5px;
        z-index: 3;
        color: #666;
    }

    .preview-container {
        overflow: auto;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .certificate-preview {
            transform: scale(0.4) !important;
        }

        .preview-container {
            min-height: 50vh;
        }
    }

    @media (max-width: 576px) {
        .certificate-preview {
            transform: scale(0.3) !important;
        }
    }
</style>

<script>
    let currentZoom = 0.75;
    const zoomStep = 0.25;
    const minZoom = 0.25;
    const maxZoom = 2.0;

    function updateZoom() {
        const preview = document.getElementById('certificate-preview');
        const zoomLevel = document.getElementById('zoom-level');

        preview.style.transform = `scale(${currentZoom})`;
        zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
    }

    function zoomIn() {
        if (currentZoom < maxZoom) {
            currentZoom += zoomStep;
            updateZoom();
        }
    }

    function zoomOut() {
        if (currentZoom > minZoom) {
            currentZoom -= zoomStep;
            updateZoom();
        }
    }

    function resetZoom() {
        currentZoom = 0.75;
        updateZoom();
    }

    // Controle de zoom por teclado
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case '+':
                case '=':
                    e.preventDefault();
                    zoomIn();
                    break;
                case '-':
                    e.preventDefault();
                    zoomOut();
                    break;
                case '0':
                    e.preventDefault();
                    resetZoom();
                    break;
            }
        }
    });

    // Ajuste automático para telas pequenas
    function adjustForScreenSize() {
        const screenWidth = window.innerWidth;
        if (screenWidth < 768) {
            currentZoom = 0.4;
        } else if (screenWidth < 576) {
            currentZoom = 0.3;
        } else {
            currentZoom = 0.75;
        }
        updateZoom();
    }

    // Ajustar zoom inicial
    window.addEventListener('load', adjustForScreenSize);
    window.addEventListener('resize', adjustForScreenSize);
</script>
@endsection
