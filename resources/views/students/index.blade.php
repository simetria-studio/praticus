@extends('layouts.admin')

@section('title', 'Alunos')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Alunos</span>
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
                            Gerenciar Alunos
                        </h4>
                        <a href="{{ route('students.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Novo Aluno
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nome, matrícula, CPF ou email...">
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
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                        <option value="transferido" {{ request('status') == 'transferido' ? 'selected' : '' }}>Transferido</option>
                                        <option value="formado" {{ request('status') == 'formado' ? 'selected' : '' }}>Formado</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="grade" class="form-label">Série</label>
                                    <input type="text"
                                           class="form-control"
                                           id="grade"
                                           name="grade"
                                           value="{{ request('grade') }}"
                                           placeholder="Ex: 1º ano">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
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
                                    <th>Aluno</th>
                                    <th>Matrícula</th>
                                    <th>Escola</th>
                                    <th>Série/Turma</th>
                                    <th>Status</th>
                                    <th>Idade</th>
                                    <th width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    @if($student->photo)
                                                        <img src="{{ $student->getPhotoUrl() }}" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            {{ $student->getInitials() }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $student->name }}</strong>
                                                    @if($student->cpf)
                                                        <br><small class="text-muted">CPF: {{ $student->formatted_cpf }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $student->enrollment }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $student->school->name }}</strong>
                                                <br><small class="text-muted">{{ $student->school->city }} - {{ $student->school->state }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $student->grade_with_class }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $student->status_badge_class }}">{{ $student->status_label }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $student->age }} anos</span>
                                            <br><small class="text-muted">{{ $student->birth_date->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('students.show', $student) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('notes.create') }}?student_id={{ $student->id }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Cadastrar Notas">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </a>
                                                <a href="{{ route('students.edit', $student) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success toggle-status"
                                                        data-student-id="{{ $student->id }}"
                                                        title="Alterar Status">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Excluir"
                                                        onclick="confirmDelete({{ $student->id }}, '{{ addslashes($student->name) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Nenhum aluno encontrado</p>
                                            <a href="{{ route('students.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                Cadastrar Primeiro Aluno
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($students->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    @endif
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
document.querySelectorAll('.toggle-status').forEach(function(button) {
    button.addEventListener('click', function() {
        const studentId = this.dataset.studentId;
        const statusBadge = this.closest('tr').querySelector('.badge');

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
                statusBadge.textContent = data.status_label;
                statusBadge.className = 'badge ' + data.badge_class;

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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao alterar status do aluno');
        });
    });
});
</script>
@endpush
@endsection
