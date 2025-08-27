@extends('layouts.admin')

@section('title', 'Detalhes da Escola')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('schools.index') }}">Escolas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Detalhes</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-school me-2"></i>
                            {{ $school->name }}
                            @if(!$school->active)
                                <span class="badge bg-danger ms-2">Inativa</span>
                            @else
                                <span class="badge bg-success ms-2">Ativa</span>
                            @endif
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('schools.edit', $school) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $school->id }}, '{{ addslashes($school->name) }}')">
                                <i class="fas fa-trash me-1"></i>
                                Excluir
                            </button>
                            <a href="{{ route('schools.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
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

                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações Básicas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Nome da Escola</label>
                                                <p class="fw-bold">{{ $school->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Código</label>
                                                <p class="fw-bold">
                                                    @if($school->code)
                                                        <span class="badge bg-secondary">{{ $school->code }}</span>
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">CNPJ</label>
                                                <p class="fw-bold">
                                                    @if($school->cnpj)
                                                        {{ $school->formatted_cnpj }}
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Telefone</label>
                                                <p class="fw-bold">
                                                    @if($school->phone)
                                                        <a href="tel:{{ $school->phone }}" class="text-decoration-none">
                                                            {{ $school->phone }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">E-mail</label>
                                                <p class="fw-bold">
                                                    @if($school->email)
                                                        <a href="mailto:{{ $school->email }}" class="text-decoration-none">
                                                            {{ $school->email }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Website</label>
                                                <p class="fw-bold">
                                                    @if($school->website)
                                                        <a href="{{ $school->website }}" target="_blank" class="text-decoration-none">
                                                            {{ $school->website }}
                                                            <i class="fas fa-external-link-alt ms-1"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Tipo</label>
                                                <p class="fw-bold">
                                                    <span class="badge bg-info">{{ $school->getTypeLabel() }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Nível de Ensino</label>
                                                <p class="fw-bold">
                                                    <span class="badge bg-warning text-dark">{{ $school->getLevelLabel() }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($school->description)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Descrição</label>
                                            <p class="fw-bold">{{ $school->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informações Rápidas -->
                        <div class="col-md-4">
                            <div class="row">
                                <!-- Status -->
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-toggle-on me-2"></i>
                                                Status
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input toggle-active"
                                                       type="checkbox"
                                                       data-school-id="{{ $school->id }}"
                                                       {{ $school->active ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2">
                                                    <span class="status-text fw-bold">{{ $school->active ? 'Ativa' : 'Inativa' }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datas -->
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-calendar me-2"></i>
                                                Datas
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <small class="text-muted">Cadastrado em:</small>
                                                <div class="fw-bold">{{ $school->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                            <div>
                                                <small class="text-muted">Atualizado em:</small>
                                                <div class="fw-bold">{{ $school->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Endereço
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Endereço Completo</label>
                                                <p class="fw-bold">{{ $school->full_address }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">CEP</label>
                                                <p class="fw-bold">{{ $school->formatted_postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Rua/Avenida</label>
                                                <p class="fw-bold">{{ $school->street }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Número</label>
                                                <p class="fw-bold">{{ $school->number }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Complemento</label>
                                                <p class="fw-bold">
                                                    @if($school->complement)
                                                        {{ $school->complement }}
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Bairro</label>
                                                <p class="fw-bold">{{ $school->neighborhood }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Cidade</label>
                                                <p class="fw-bold">{{ $school->city }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Estado</label>
                                                <p class="fw-bold">{{ $school->state }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">País</label>
                                                <p class="fw-bold">{{ $school->country }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($school->latitude && $school->longitude)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Coordenadas</label>
                                                    <p class="fw-bold">
                                                        {{ $school->latitude }}, {{ $school->longitude }}
                                                        <a href="https://maps.google.com/?q={{ $school->latitude }},{{ $school->longitude }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary ms-2">
                                                            <i class="fas fa-map-marked-alt me-1"></i>
                                                            Ver no Google Maps
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                <p>Tem certeza que deseja excluir a escola <strong id="schoolName"></strong>?</p>
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
function confirmDelete(schoolId, schoolName) {
    document.getElementById('schoolName').textContent = schoolName;
    document.getElementById('deleteForm').action = `/schools/${schoolId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Toggle Active Status
document.querySelectorAll('.toggle-active').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const schoolId = this.dataset.schoolId;
        const statusText = this.closest('.card-body').querySelector('.status-text');

        fetch(`/schools/${schoolId}/toggle-active`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusText.textContent = data.active ? 'Ativa' : 'Inativa';

                // Update badge in header
                const headerBadge = document.querySelector('.card-header .badge');
                if (headerBadge) {
                    headerBadge.className = data.active ? 'badge bg-success ms-2' : 'badge bg-danger ms-2';
                    headerBadge.textContent = data.active ? 'Ativa' : 'Inativa';
                }

                // Toast notification
                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-bg-success border-0 position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 1055;';
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            ${data.message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                new bootstrap.Toast(toast).show();

                setTimeout(() => {
                    toast.remove();
                }, 5000);
            } else {
                alert('Erro: ' + data.message);
                this.checked = !this.checked;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao alterar status da escola');
            this.checked = !this.checked;
        });
    });
});
</script>
@endpush
@endsection
