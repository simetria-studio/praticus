@extends('layouts.admin')

@section('title', 'Certificados')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Certificados</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            Gerenciar Certificados
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('certificates.verify') }}" class="btn btn-outline-light btn-sm" target="_blank">
                                <i class="fas fa-search me-1"></i>
                                Verificar Certificado
                            </a>
                            <a href="{{ route('certificates.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Gerar Certificado
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('certificates.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nº certificado, aluno, código...">
                                </div>
                                <div class="col-md-3">
                                    <label for="school_id" class="form-label">Escola</label>
                                    <select class="form-select" id="school_id" name="school_id">
                                        <option value="">Todas as escolas</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="certificate_type" class="form-label">Tipo</label>
                                    <select class="form-select" id="certificate_type" name="certificate_type">
                                        <option value="">Todos</option>
                                        <option value="conclusao" {{ request('certificate_type') == 'conclusao' ? 'selected' : '' }}>Conclusão</option>
                                        <option value="historico" {{ request('certificate_type') == 'historico' ? 'selected' : '' }}>Histórico</option>
                                        <option value="declaracao" {{ request('certificate_type') == 'declaracao' ? 'selected' : '' }}>Declaração</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="emitido" {{ request('status') == 'emitido' ? 'selected' : '' }}>Emitido</option>
                                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="reemitido" {{ request('status') == 'reemitido' ? 'selected' : '' }}>Reemitido</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Alertas -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nº Certificado</th>
                                    <th>Aluno</th>
                                    <th>Escola</th>
                                    <th>Tipo/Nível</th>
                                    <th>Conclusão</th>
                                    <th>Status</th>
                                    <th width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificates as $certificate)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $certificate->certificate_number }}</strong>
                                                <br><small class="text-muted">{{ $certificate->verification_code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $certificate->student_name }}</strong>
                                                @if($certificate->student_cpf)
                                                    <br><small class="text-muted">CPF: {{ $certificate->formatted_student_cpf }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $certificate->school_name }}</strong>
                                                <br><small class="text-muted">{{ $certificate->issue_city }} - {{ $certificate->issue_state }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $certificate->certificate_type_label }}</span>
                                            <br><span class="badge bg-warning text-dark">{{ $certificate->course_level_label }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $certificate->completion_year }}</span>
                                            <br><small class="text-muted">{{ $certificate->formatted_completion_date }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $certificate->status_badge_class }}">{{ $certificate->status_label }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group-vertical" role="group">
                                                <div class="btn-group mb-1" role="group">
                                                    <a href="{{ route('certificates.show', $certificate) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('certificates.preview', $certificate) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Preview">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                    <a href="{{ route('certificates.download', $certificate) }}"
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Download PDF">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    @if($certificate->isEmitted())
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-warning"
                                                                title="Cancelar"
                                                                onclick="confirmCancel({{ $certificate->id }}, '{{ addslashes($certificate->certificate_number) }}')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="btn-group" role="group">
                                                    @if($certificate->isEmitted() || $certificate->isCancelled())
                                                        <form method="POST" action="{{ route('certificates.reissue', $certificate) }}" style="display: inline;">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    title="Reemitir"
                                                                    onclick="return confirm('Deseja reemitir este certificado?')">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Excluir"
                                                            onclick="confirmDelete({{ $certificate->id }}, '{{ addslashes($certificate->certificate_number) }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Nenhum certificado encontrado</p>
                                            <a href="{{ route('certificates.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                Gerar Primeiro Certificado
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($certificates->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $certificates->appends(request()->query())->links() }}
                        </div>
                    @endif
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

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o certificado <strong id="deleteCertificateNumber"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
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

function confirmDelete(certificateId, certificateNumber) {
    document.getElementById('deleteCertificateNumber').textContent = certificateNumber;
    document.getElementById('deleteForm').action = `/certificates/${certificateId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
