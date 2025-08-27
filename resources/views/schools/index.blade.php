@extends('layouts.admin')

@section('title', 'Escolas')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Escolas</span>
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
                            Gerenciar Escolas
                        </h4>
                        <a href="{{ route('schools.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Nova Escola
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('schools.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nome, código, CNPJ ou cidade...">
                                </div>
                                <div class="col-md-2">
                                    <label for="type" class="form-label">Tipo</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="">Todos</option>
                                        <option value="publica" {{ request('type') == 'publica' ? 'selected' : '' }}>Pública</option>
                                        <option value="privada" {{ request('type') == 'privada' ? 'selected' : '' }}>Privada</option>
                                        <option value="federal" {{ request('type') == 'federal' ? 'selected' : '' }}>Federal</option>
                                        <option value="estadual" {{ request('type') == 'estadual' ? 'selected' : '' }}>Estadual</option>
                                        <option value="municipal" {{ request('type') == 'municipal' ? 'selected' : '' }}>Municipal</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="level" class="form-label">Nível</label>
                                    <select class="form-select" id="level" name="level">
                                        <option value="">Todos</option>
                                        <option value="infantil" {{ request('level') == 'infantil' ? 'selected' : '' }}>Infantil</option>
                                        <option value="fundamental" {{ request('level') == 'fundamental' ? 'selected' : '' }}>Fundamental</option>
                                        <option value="medio" {{ request('level') == 'medio' ? 'selected' : '' }}>Médio</option>
                                        <option value="superior" {{ request('level') == 'superior' ? 'selected' : '' }}>Superior</option>
                                        <option value="tecnico" {{ request('level') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                                        <option value="todos" {{ request('level') == 'todos' ? 'selected' : '' }}>Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="active" class="form-label">Status</label>
                                    <select class="form-select" id="active" name="active">
                                        <option value="">Todos</option>
                                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Ativa</option>
                                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inativa</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('schools.index') }}" class="btn btn-outline-secondary">
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
                                    <th>Nome</th>
                                    <th>Código</th>
                                    <th>Tipo</th>
                                    <th>Nível</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schools as $school)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="school-icon me-2">
                                                    <i class="fas fa-school text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $school->name }}</strong>
                                                    @if($school->cnpj)
                                                        <br><small class="text-muted">CNPJ: {{ $school->formatted_cnpj }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $school->code ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $school->getTypeLabel() }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $school->getLevelLabel() }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $school->city }}</strong>
                                                <br><small class="text-muted">{{ $school->state }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-active"
                                                       type="checkbox"
                                                       data-school-id="{{ $school->id }}"
                                                       {{ $school->active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <span class="status-text">{{ $school->active ? 'Ativa' : 'Inativa' }}</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('schools.show', $school) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('schools.edit', $school) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Excluir"
                                                        onclick="confirmDelete({{ $school->id }}, '{{ addslashes($school->name) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Nenhuma escola encontrada</p>
                                            <a href="{{ route('schools.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                Cadastrar Primeira Escola
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($schools->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $schools->appends(request()->query())->links() }}
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
        const statusText = this.closest('td').querySelector('.status-text');

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
