{{--
    EXEMPLO: Como adicionar botões para certificados em landscape
    Adicione este código em suas views de listagem de certificados
--}}

{{-- Exemplo 1: Botões individuais para cada certificado --}}
<div class="certificate-actions">
    {{-- Botões originais (portrait) --}}
    <a href="{{ route('certificates.show', $certificate->id) }}"
       class="btn btn-primary btn-sm"
       target="_blank">
        <i class="fas fa-eye"></i> Ver Certificado
    </a>

    <a href="{{ route('certificates.pdf', $certificate->id) }}"
       class="btn btn-danger btn-sm">
        <i class="fas fa-download"></i> PDF Portrait
    </a>

    {{-- NOVOS botões landscape --}}
    <a href="{{ route('certificates.landscape', $certificate->id) }}"
       class="btn btn-success btn-sm"
       target="_blank">
        <i class="fas fa-desktop"></i> Ver Landscape
    </a>

    <a href="{{ route('certificates.landscape.pdf', $certificate->id) }}"
       class="btn btn-warning btn-sm">
        <i class="fas fa-download"></i> PDF Landscape
    </a>
</div>

{{-- Exemplo 2: Dropdown com opções --}}
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="fas fa-cog"></i> Ações do Certificado
    </button>
    <div class="dropdown-menu">
        {{-- Visualização --}}
        <h6 class="dropdown-header">Visualizar</h6>
        <a class="dropdown-item" href="{{ route('certificates.show', $certificate->id) }}" target="_blank">
            <i class="fas fa-eye"></i> Portrait (Vertical)
        </a>
        <a class="dropdown-item" href="{{ route('certificates.landscape', $certificate->id) }}" target="_blank">
            <i class="fas fa-desktop"></i> Landscape (Horizontal)
        </a>

        <div class="dropdown-divider"></div>

        {{-- Download PDF --}}
        <h6 class="dropdown-header">Download PDF</h6>
        <a class="dropdown-item" href="{{ route('certificates.pdf', $certificate->id) }}">
            <i class="fas fa-download"></i> PDF Portrait
        </a>
        <a class="dropdown-item" href="{{ route('certificates.landscape.pdf', $certificate->id) }}">
            <i class="fas fa-download"></i> PDF Landscape
        </a>

        <div class="dropdown-divider"></div>

        {{-- Stream (Visualizar PDF) --}}
        <h6 class="dropdown-header">Visualizar PDF</h6>
        <a class="dropdown-item" href="{{ route('certificates.stream', $certificate->id) }}" target="_blank">
            <i class="fas fa-external-link-alt"></i> Stream Portrait
        </a>
        <a class="dropdown-item" href="{{ route('certificates.landscape.stream', $certificate->id) }}" target="_blank">
            <i class="fas fa-external-link-alt"></i> Stream Landscape
        </a>
    </div>
</div>

{{-- Exemplo 3: Cards com preview --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-portrait"></i> Formato Portrait (Vertical)
            </div>
            <div class="card-body text-center">
                <div class="certificate-preview portrait-preview">
                    <i class="fas fa-file-alt fa-3x text-primary"></i>
                    <p>Formato tradicional vertical</p>
                </div>
                <a href="{{ route('certificates.show', $certificate->id) }}"
                   class="btn btn-primary btn-sm" target="_blank">
                    Ver
                </a>
                <a href="{{ route('certificates.pdf', $certificate->id) }}"
                   class="btn btn-outline-primary btn-sm">
                    PDF
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-landscape"></i> Formato Landscape (Horizontal)
            </div>
            <div class="card-body text-center">
                <div class="certificate-preview landscape-preview">
                    <i class="fas fa-desktop fa-3x text-success"></i>
                    <p>Formato horizontal otimizado</p>
                </div>
                <a href="{{ route('certificates.landscape', $certificate->id) }}"
                   class="btn btn-success btn-sm" target="_blank">
                    Ver
                </a>
                <a href="{{ route('certificates.landscape.pdf', $certificate->id) }}"
                   class="btn btn-outline-success btn-sm">
                    PDF
                </a>
            </div>
        </div>
    </div>
</div>

{{-- CSS Personalizado para os previews --}}
<style>
.certificate-preview {
    padding: 20px;
    border: 2px dashed #dee2e6;
    border-radius: 5px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.certificate-preview:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.portrait-preview {
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.landscape-preview {
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.certificate-actions .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .certificate-actions .btn {
        display: block;
        width: 100%;
        margin-right: 0;
    }
}
</style>

{{-- JavaScript para funcionalidades extras --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona confirmação antes de gerar PDF
    document.querySelectorAll('a[href*="/pdf"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirm('Deseja gerar o PDF do certificado?')) {
                e.preventDefault();
            }
        });
    });

    // Adiciona loading nos botões de visualização
    document.querySelectorAll('a[target="_blank"]').forEach(function(link) {
        link.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
            this.classList.add('disabled');

            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('disabled');
            }, 2000);
        });
    });
});
</script>
