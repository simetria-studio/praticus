@extends('layouts.admin')

@section('title', 'Detalhes do Certificado')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Certificados</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Detalhes</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            Certificado Nº {{ $certificate->certificate_number }}
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('certificates.preview', $certificate) }}"
                               class="btn btn-outline-light btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Preview
                            </a>
                            <a href="{{ route('certificates.view-pdf', $certificate) }}"
                               class="btn btn-outline-light btn-sm"
                               target="_blank">
                                <i class="fas fa-code me-1"></i>
                                Ver PDF (CSS)
                            </a>
                            <a href="{{ route('certificates.download', $certificate) }}"
                               class="btn btn-outline-light btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Download PDF
                            </a>
                            <a href="{{ route('certificates.index') }}"
                               class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Dados do Certificado -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-certificate me-2"></i>
                                        Dados do Certificado
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Número:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->certificate_number }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Tipo:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-info">{{ $certificate->certificate_type_label }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Nível:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-warning text-dark">{{ $certificate->course_level_label }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Ano:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->completion_year }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Data Conclusão:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->formatted_completion_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Data Emissão:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->formatted_issue_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Status:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge {{ $certificate->status_badge_class }}">{{ $certificate->status_label }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Código Verificação:</strong></div>
                                        <div class="col-sm-8">
                                            <code>{{ $certificate->verification_code }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados do Aluno -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Dados do Aluno
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Nome:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->student_name }}</div>
                                    </div>
                                    @if($certificate->student_cpf)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>CPF:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->formatted_student_cpf }}</div>
                                        </div>
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Nascimento:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->formatted_student_birth_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Naturalidade:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->student_birth_place }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Nacionalidade:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->student_nationality }}</div>
                                    </div>
                                    @if($certificate->father_name)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>Pai:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->father_name }}</div>
                                        </div>
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Mãe:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->mother_name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Dados da Escola -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-school me-2"></i>
                                        Dados da Escola
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Nome:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->school_name }}</div>
                                    </div>
                                    @if($certificate->school_cnpj)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>CNPJ:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->formatted_school_cnpj }}</div>
                                        </div>
                                    @endif
                                    @if($certificate->school_inep)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>INEP:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->school_inep }}</div>
                                        </div>
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Endereço:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->school_address }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Cidade:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->issue_city }} - {{ $certificate->issue_state }}</div>
                                    </div>
                                    @if($certificate->school_authorization)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>Autorização:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->school_authorization }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Autoridades -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Autoridades
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Diretor:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->director_name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Cargo:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->director_title }}</div>
                                    </div>
                                    @if($certificate->secretary_name)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>Secretário:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->secretary_name }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>Cargo:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->secretary_title }}</div>
                                        </div>
                                    @endif
                                    @if($certificate->observations)
                                        <div class="row mb-3">
                                            <div class="col-sm-4"><strong>Observações:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->observations }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Ações
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('certificates.preview', $certificate) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Preview
                                        </a>
                                        <a href="{{ route('certificates.download', $certificate) }}"
                                           class="btn btn-success">
                                            <i class="fas fa-download me-1"></i>
                                            Download PDF
                                        </a>

                                        @if($certificate->isEmitted())
                                            <button type="button"
                                                    class="btn btn-warning"
                                                    onclick="confirmCancel({{ $certificate->id }}, '{{ addslashes($certificate->certificate_number) }}')">
                                                <i class="fas fa-ban me-1"></i>
                                                Cancelar
                                            </button>
                                        @endif

                                        @if($certificate->isEmitted() || $certificate->isCancelled())
                                            <form method="POST" action="{{ route('certificates.reissue', $certificate) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-primary"
                                                        onclick="return confirm('Deseja reemitir este certificado?')">
                                                    <i class="fas fa-redo me-1"></i>
                                                    Reemitir
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('certificates.verify') }}?verification_code={{ $certificate->verification_code }}"
                                           class="btn btn-info"
                                           target="_blank">
                                            <i class="fas fa-search me-1"></i>
                                            Verificar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Cancelamento -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cancelamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar o certificado <strong id="cancelCertificateNumber"></strong>?</p>
                <p class="text-warning"><small>O certificado será marcado como cancelado.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                <form id="cancelForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">Cancelar Certificado</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmCancel(certificateId, certificateNumber) {
    document.getElementById('cancelCertificateNumber').textContent = certificateNumber;
    document.getElementById('cancelForm').action = `/certificates/${certificateId}/cancel`;
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}
</script>
@endpush
@endsection
