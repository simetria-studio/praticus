@extends('layouts.admin')

@section('title', 'Detalhes da Nota')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></span>
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
                            <i class="fas fa-star me-2"></i>
                            Detalhes da Nota
                            <span class="badge {{ $note->performance_class }} ms-2">{{ $note->performance_level }}</span>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('notes.edit', $note) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $note->id }}, '{{ addslashes($note->student->name) }}', '{{ addslashes($note->subject_name) }}')">
                                <i class="fas fa-trash me-1"></i>
                                Excluir
                            </button>
                            <a href="{{ route('notes.index') }}" class="btn btn-light btn-sm">
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
                        <!-- Informações da Nota -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Informações da Avaliação
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Disciplina</label>
                                                <p class="fw-bold h5 text-primary">{{ $note->subject_name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Período</label>
                                                <p class="fw-bold">{{ $note->period_name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Tipo de Avaliação</label>
                                                <p class="fw-bold">{{ $note->evaluation_type_name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Data da Avaliação</label>
                                                <p class="fw-bold">{{ $note->evaluation_date->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Ano Letivo</label>
                                                <p class="fw-bold">{{ $note->school_year }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Turma</label>
                                                <p class="fw-bold">{{ $note->class ?: 'Não informada' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($note->observations)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Observações</label>
                                            <p class="fw-bold">{{ $note->observations }}</p>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Peso</label>
                                                <p class="fw-bold">{{ number_format($note->weight, 2, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Status</label>
                                                <p class="fw-bold">
                                                    <span class="badge badge-{{ $note->status === 'ativa' ? 'success' : ($note->status === 'cancelada' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($note->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações da Nota e Desempenho -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Nota e Desempenho
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Nota em destaque -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-center align-items-end mb-2">
                                            <span class="display-3 fw-bold text-primary">{{ number_format($note->grade, 2, ',', '.') }}</span>
                                            <span class="h4 text-muted ms-2">/ {{ number_format($note->max_grade, 2, ',', '.') }}</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 15px;">
                                            <div class="progress-bar bg-{{ $note->percentage >= 60 ? 'success' : ($note->percentage >= 40 ? 'warning' : 'danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ $note->percentage }}%"
                                                 aria-valuenow="{{ $note->percentage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ number_format($note->percentage, 1, ',', '.') }}%
                                            </div>
                                        </div>
                                        <h5 class="text-{{ $note->performance_class }}">{{ $note->performance_level }}</h5>
                                    </div>

                                    <!-- Média da disciplina no período -->
                                    @if($average)
                                        <div class="border-top pt-3">
                                            <label class="form-label text-muted">Média da Disciplina no Período</label>
                                            <h4 class="fw-bold text-{{ $average >= 6 ? 'success' : ($average >= 4 ? 'warning' : 'danger') }}">
                                                {{ number_format($average, 2, ',', '.') }}
                                            </h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Aluno -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Informações do Aluno
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Nome do Aluno</label>
                                                <p class="fw-bold">
                                                    <a href="{{ route('students.show', $note->student) }}" class="text-decoration-none">
                                                        {{ $note->student->name }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Matrícula</label>
                                                <p class="fw-bold">{{ $note->student->enrollment }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Série/Turma</label>
                                                <p class="fw-bold">{{ $note->student->grade_with_class }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Escola</label>
                                                <p class="fw-bold">
                                                    <a href="{{ route('schools.show', $note->student->school) }}" class="text-decoration-none">
                                                        {{ $note->student->school->name }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('students.show', $note->student) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user me-1"></i>
                                            Ver Perfil do Aluno
                                        </a>
                                        <a href="{{ route('notes.student-report', $note->student) }}?school_year={{ $note->school_year }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-chart-bar me-1"></i>
                                            Boletim do Aluno
                                        </a>
                                        <a href="{{ route('notes.create') }}?student_id={{ $note->student->id }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-plus me-1"></i>
                                            Nova Nota para Este Aluno
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notas Relacionadas -->
                    @if($relatedNotes->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-list me-2"></i>
                                            Outras Notas - {{ $note->subject_name }} ({{ $note->period_name }})
                                            <span class="badge bg-secondary ms-2">{{ $relatedNotes->count() }}</span>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Data</th>
                                                        <th>Nota</th>
                                                        <th>Percentual</th>
                                                        <th>Peso</th>
                                                        <th>Desempenho</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($relatedNotes as $relatedNote)
                                                        <tr>
                                                            <td>{{ $relatedNote->evaluation_type_name }}</td>
                                                            <td>{{ $relatedNote->evaluation_date->format('d/m/Y') }}</td>
                                                            <td class="fw-bold">{{ number_format($relatedNote->grade, 2, ',', '.') }}/{{ number_format($relatedNote->max_grade, 2, ',', '.') }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $relatedNote->percentage >= 60 ? 'success' : ($relatedNote->percentage >= 40 ? 'warning' : 'danger') }}">
                                                                    {{ number_format($relatedNote->percentage, 1, ',', '.') }}%
                                                                </span>
                                                            </td>
                                                            <td>{{ number_format($relatedNote->weight, 2, ',', '.') }}</td>
                                                            <td>
                                                                <span class="text-{{ $relatedNote->performance_class }}">
                                                                    {{ $relatedNote->performance_level }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('notes.show', $relatedNote) }}" class="btn btn-outline-primary btn-sm" title="Ver">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('notes.edit', $relatedNote) }}" class="btn btn-outline-warning btn-sm" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <p>Tem certeza que deseja excluir a nota de <strong id="studentName"></strong> em <strong id="subjectName"></strong>?</p>
                <p class="text-danger"><small><i class="fas fa-exclamation-triangle me-1"></i> Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
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
function confirmDelete(noteId, studentName, subjectName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('subjectName').textContent = subjectName;

    const form = document.getElementById('deleteForm');
    form.action = `/notes/${noteId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
@endsection
