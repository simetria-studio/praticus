@extends('layouts.admin')

@section('title', 'Perfil do Aluno')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Perfil</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-graduate me-2"></i>
                            {{ $student->name }}
                            <span class="badge {{ $student->status_badge_class }} ms-2">{{ $student->status_label }}</span>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('notes.create') }}?student_id={{ $student->id }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-clipboard-list me-1"></i>
                                Cadastrar Notas
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('notes.historical-report', $student) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-scroll me-1"></i>
                                    Histórico Escolar
                                </a>
                                <a href="{{ route('notes.historical-report.pdf', $student) }}" class="btn btn-outline-primary btn-sm" title="Baixar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                            <a href="{{ route('notes.student-report', $student) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-chart-bar me-1"></i>
                                Boletim
                            </a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $student->id }}, '{{ addslashes($student->name) }}')">
                                <i class="fas fa-trash me-1"></i>
                                Excluir
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
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
                        <!-- Foto e Informações Principais -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Perfil do Aluno
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($student->photo)
                                            <img src="{{ $student->getPhotoUrl() }}"
                                                 alt="{{ $student->name }}"
                                                 class="rounded-circle mb-3"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                 style="width: 150px; height: 150px; font-size: 3rem;">
                                                {{ $student->getInitials() }}
                                            </div>
                                        @endif
                                    </div>

                                    <h5 class="fw-bold">{{ $student->name }}</h5>
                                    <p class="text-muted">{{ $student->enrollment }}</p>

                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="fw-bold">{{ $student->age }}</h6>
                                                <small class="text-muted">Anos</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="fw-bold">{{ $student->grade_with_class }}</h6>
                                            <small class="text-muted">Série/Turma</small>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input toggle-status"
                                                   type="checkbox"
                                                   data-student-id="{{ $student->id }}"
                                                   {{ $student->isActive() ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2">
                                                <span class="status-text fw-bold">{{ $student->status_label }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Detalhadas -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Dados Pessoais -->
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-id-card me-2"></i>
                                                Dados Pessoais
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Nome Completo</label>
                                                        <p class="fw-bold">{{ $student->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Data de Nascimento</label>
                                                        <p class="fw-bold">{{ $student->birth_date->format('d/m/Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Sexo</label>
                                                        <p class="fw-bold">
                                                            @if($student->gender)
                                                                {{ $student->gender_label }}
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
                                                        <label class="form-label text-muted">CPF</label>
                                                        <p class="fw-bold">
                                                            @if($student->cpf)
                                                                {{ $student->formatted_cpf }}
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">RG</label>
                                                        <p class="fw-bold">
                                                            @if($student->rg)
                                                                {{ $student->rg }}
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Telefone</label>
                                                        <p class="fw-bold">
                                                            @if($student->phone)
                                                                <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                                                    {{ $student->phone }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">E-mail</label>
                                                        <p class="fw-bold">
                                                            @if($student->email)
                                                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                                                    {{ $student->email }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dados Acadêmicos -->
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-graduation-cap me-2"></i>
                                                Dados Acadêmicos
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Matrícula</label>
                                                        <p class="fw-bold">
                                                            <span class="badge bg-secondary">{{ $student->enrollment }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Escola</label>
                                                        <p class="fw-bold">
                                                            <a href="{{ route('schools.show', $student->school) }}" class="text-decoration-none">
                                                                {{ $student->school->name }}
                                                            </a>
                                                            <br><small class="text-muted">{{ $student->school->city }} - {{ $student->school->state }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Série</label>
                                                        <p class="fw-bold">{{ $student->grade }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Turma</label>
                                                        <p class="fw-bold">
                                                            @if($student->class)
                                                                {{ $student->class }}
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Ano Letivo</label>
                                                        <p class="fw-bold">{{ $student->school_year }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Data da Matrícula</label>
                                                        <p class="fw-bold">
                                                            @if($student->enrollment_date)
                                                                {{ $student->enrollment_date->format('d/m/Y') }}
                                                            @else
                                                                <span class="text-muted">Não informado</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
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
                                                <p class="fw-bold">{{ $student->full_address }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">CEP</label>
                                                <p class="fw-bold">{{ $student->formatted_postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Responsável -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users me-2"></i>
                                        Dados do Responsável
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Nome</label>
                                                <p class="fw-bold">{{ $student->guardian_name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Telefone</label>
                                                <p class="fw-bold">
                                                    <a href="tel:{{ $student->guardian_phone }}" class="text-decoration-none">
                                                        {{ $student->guardian_phone }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Parentesco</label>
                                                <p class="fw-bold">
                                                    <span class="badge bg-info">{{ $student->guardian_relationship_label }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">CPF</label>
                                                <p class="fw-bold">
                                                    @if($student->guardian_cpf)
                                                        {{ $student->formatted_guardian_cpf }}
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">E-mail</label>
                                                <p class="fw-bold">
                                                    @if($student->guardian_email)
                                                        <a href="mailto:{{ $student->guardian_email }}" class="text-decoration-none">
                                                            {{ $student->guardian_email }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações Adicionais -->
                    @if($student->medical_info || $student->observations)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Informações Adicionais
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @if($student->medical_info)
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Informações Médicas</label>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            {{ $student->medical_info }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($student->observations)
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Observações</label>
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            {{ $student->observations }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Datas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar me-2"></i>
                                        Histórico
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <small class="text-muted">Cadastrado em:</small>
                                                <div class="fw-bold">{{ $student->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <small class="text-muted">Última atualização:</small>
                                                <div class="fw-bold">{{ $student->updated_at->format('d/m/Y H:i') }}</div>
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
                <p>Tem certeza que deseja excluir o aluno <strong id="studentName"></strong>?</p>
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
function confirmDelete(studentId, studentName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('deleteForm').action = `/students/${studentId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Toggle Status
document.querySelectorAll('.toggle-status').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const studentId = this.dataset.studentId;
        const statusText = this.closest('.card-body').querySelector('.status-text');

        fetch(`/students/${studentId}/toggle-status`, {
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
                statusText.textContent = data.status_label;

                // Update badge in header
                const headerBadge = document.querySelector('.card-header .badge');
                if (headerBadge) {
                    headerBadge.className = 'badge ' + data.badge_class + ' ms-2';
                    headerBadge.textContent = data.status_label;
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
            alert('Erro ao alterar status do aluno');
            this.checked = !this.checked;
        });
    });
});
</script>
@endpush
@endsection
