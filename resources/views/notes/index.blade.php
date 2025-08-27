@extends('layouts.admin')

@section('title', 'Notas')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Notas</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Gerenciar Notas
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('notes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Nova Nota
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('notes.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nome do aluno, matrícula...">
                                </div>
                                <div class="col-md-2">
                                    <label for="subject" class="form-label">Disciplina</label>
                                    <select class="form-select" id="subject" name="subject">
                                        <option value="">Todas</option>
                                        @foreach($subjects as $key => $label)
                                            <option value="{{ $key }}" {{ request('subject') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="period" class="form-label">Período</label>
                                    <select class="form-select" id="period" name="period">
                                        <option value="">Todos</option>
                                        @foreach($periods as $key => $label)
                                            <option value="{{ $key }}" {{ request('period') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="school_year" class="form-label">Ano Letivo</label>
                                    <select class="form-select" id="school_year" name="school_year">
                                        <option value="">Todos</option>
                                        @foreach($schoolYears as $year)
                                            <option value="{{ $year }}" {{ request('school_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="evaluation_type" class="form-label">Tipo</label>
                                    <select class="form-select" id="evaluation_type" name="evaluation_type">
                                        <option value="">Todos</option>
                                        @foreach($evaluationTypes as $key => $label)
                                            <option value="{{ $key }}" {{ request('evaluation_type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary btn-sm">
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

                    <!-- Estatísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>{{ $notes->total() }}</h5>
                                            <small>Total de Notas</small>
                                        </div>
                                        <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>{{ $notes->where('percentage', '>=', 60)->count() }}</h5>
                                            <small>Aprovados (≥60%)</small>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>{{ $notes->whereBetween('percentage', [40, 59])->count() }}</h5>
                                            <small>Recuperação (40-59%)</small>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>{{ $notes->where('percentage', '<', 40)->count() }}</h5>
                                            <small>Reprovados (<40%)</small>
                                        </div>
                                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Disciplina</th>
                                    <th>Período</th>
                                    <th>Nota</th>
                                    <th>Tipo</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notes as $note)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $note->student->name }}</strong>
                                                <br><small class="text-muted">{{ $note->student->enrollment }}</small>
                                                @if($note->student->school)
                                                    <br><small class="text-muted">{{ $note->student->school->name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $subjects[$note->subject] ?? $note->subject }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $periods[$note->period] ?? $note->period }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="{{ $note->concept_color }}">
                                                    {{ $note->formatted_grade }}/{{ $note->formatted_max_grade }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $note->formatted_percentage }} - {{ $note->concept }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $note->evaluation_type_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $note->formatted_evaluation_date }}
                                                <br><small class="text-muted">{{ $note->school_year }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $note->status_badge_class }}">
                                                {{ $note->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group-vertical" role="group">
                                                <div class="btn-group mb-1" role="group">
                                                    <a href="{{ route('notes.show', $note) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('notes.edit', $note) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('notes.student-report', $note->student) }}"
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Boletim">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('notes.historical-report', $note->student) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Histórico Escolar">
                                                            <i class="fas fa-scroll"></i>
                                                        </a>
                                                        <a href="{{ route('notes.historical-report.pdf', $note->student) }}"
                                                           class="btn btn-sm btn-outline-danger"
                                                           title="Baixar PDF do Histórico">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Excluir"
                                                            onclick="confirmDelete({{ $note->id }}, '{{ addslashes($note->student->name) }}', '{{ addslashes($subjects[$note->subject] ?? $note->subject) }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Nenhuma nota encontrada</p>
                                            <a href="{{ route('notes.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                Cadastrar Primeira Nota
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($notes->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $notes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a nota de <strong id="studentName"></strong> em <strong id="subjectName"></strong>?</p>
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

<script>
function confirmDelete(noteId, studentName, subjectName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('subjectName').textContent = subjectName;

    const form = document.getElementById('deleteForm');
    form.action = `/notes/${noteId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
