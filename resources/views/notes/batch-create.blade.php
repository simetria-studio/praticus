@extends('layouts.admin')

@section('title', 'Lançamento de Notas em Lote')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Lançamento em Lote</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>
                            Lançamento de Notas em Lote
                        </h4>
                        <a href="{{ route('notes.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Alertas -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Erro:</strong> Por favor, corrija os erros abaixo:
                            <ul class="mt-2 mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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

                    <form method="POST" action="{{ route('notes.batch.store') }}" id="batchNotesForm">
                        @csrf

                        <!-- Dados da Avaliação -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Dados da Avaliação
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Disciplina <span class="text-danger">*</span></label>
                                            <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                                <option value="">Selecione...</option>
                                                @foreach($subjects as $key => $name)
                                                    <option value="{{ $key }}" {{ old('subject') == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="period" class="form-label">Período <span class="text-danger">*</span></label>
                                            <select class="form-select @error('period') is-invalid @enderror" id="period" name="period" required>
                                                <option value="">Selecione...</option>

                                                <!-- Avaliações do 1º Semestre -->
                                                <optgroup label="1º Semestre">
                                                    <option value="1_ava" {{ old('period') == '1_ava' ? 'selected' : '' }}>1ª AVA</option>
                                                    <option value="2_ava" {{ old('period') == '2_ava' ? 'selected' : '' }}>2ª AVA</option>
                                                    <option value="3_ava" {{ old('period') == '3_ava' ? 'selected' : '' }}>3ª AVA</option>
                                                    <option value="4_ava" {{ old('period') == '4_ava' ? 'selected' : '' }}>4ª AVA</option>
                                                </optgroup>

                                                <!-- Avaliações do 2º Semestre -->
                                                <optgroup label="2º Semestre">
                                                    <option value="5_ava" {{ old('period') == '5_ava' ? 'selected' : '' }}>5ª AVA</option>
                                                    <option value="6_ava" {{ old('period') == '6_ava' ? 'selected' : '' }}>6ª AVA</option>
                                                    <option value="7_ava" {{ old('period') == '7_ava' ? 'selected' : '' }}>7ª AVA</option>
                                                    <option value="8_ava" {{ old('period') == '8_ava' ? 'selected' : '' }}>8ª AVA</option>
                                                </optgroup>

                                                <!-- Recuperações -->
                                                <optgroup label="Recuperações">
                                                    <option value="recuperacao_1_semestre" {{ old('period') == 'recuperacao_1_semestre' ? 'selected' : '' }}>Recuperação 1º Semestre</option>
                                                    <option value="recuperacao_2_semestre" {{ old('period') == 'recuperacao_2_semestre' ? 'selected' : '' }}>Recuperação 2º Semestre</option>
                                                </optgroup>

                                                <!-- Prova Final -->
                                                <optgroup label="Avaliações Finais">
                                                    <option value="prova_final" {{ old('period') == 'prova_final' ? 'selected' : '' }}>Prova Final</option>
                                                </optgroup>
                                            </select>
                                            @error('period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="evaluation_type" class="form-label">Tipo de Avaliação <span class="text-danger">*</span></label>
                                            <select class="form-select @error('evaluation_type') is-invalid @enderror" id="evaluation_type" name="evaluation_type" required>
                                                <option value="">Selecione...</option>
                                                @foreach($evaluationTypes as $key => $name)
                                                    <option value="{{ $key }}" {{ old('evaluation_type') == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evaluation_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="max_grade" class="form-label">Nota Máxima <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('max_grade') is-invalid @enderror"
                                                   id="max_grade"
                                                   name="max_grade"
                                                   value="{{ old('max_grade', 10) }}"
                                                   step="0.01"
                                                   min="0.01"
                                                   max="999.99"
                                                   required>
                                            @error('max_grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="evaluation_date" class="form-label">Data da Avaliação <span class="text-danger">*</span></label>
                                            <input type="date"
                                                   class="form-control @error('evaluation_date') is-invalid @enderror"
                                                   id="evaluation_date"
                                                   name="evaluation_date"
                                                   value="{{ old('evaluation_date', date('Y-m-d')) }}"
                                                   required>
                                            @error('evaluation_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="school_year" class="form-label">Ano Letivo <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('school_year') is-invalid @enderror"
                                                   id="school_year"
                                                   name="school_year"
                                                   value="{{ old('school_year', date('Y')) }}"
                                                   min="2020"
                                                   max="2030"
                                                   required>
                                            @error('school_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Peso</label>
                                            <input type="number"
                                                   class="form-control @error('weight') is-invalid @enderror"
                                                   id="weight"
                                                   name="weight"
                                                   value="{{ old('weight', 1) }}"
                                                   step="0.01"
                                                   min="0.01"
                                                   max="10.00">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="observations" class="form-label">Observações</label>
                                            <textarea class="form-control @error('observations') is-invalid @enderror"
                                                      id="observations"
                                                      name="observations"
                                                      rows="2"
                                                      placeholder="Observações sobre a avaliação...">{{ old('observations') }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seleção de Escola e Turma -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-school me-2"></i>
                                    Seleção de Escola e Turma
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="school_id" class="form-label">Escola <span class="text-danger">*</span></label>
                                            <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                                <option value="">Selecione uma escola...</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="class_id" class="form-label">Turma <span class="text-danger">*</span></label>
                                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required disabled>
                                                <option value="">Primeiro selecione uma escola</option>
                                            </select>
                                            @error('class_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seleção de Alunos -->
                        <div class="card mb-4" id="studentsSection" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Seleção de Alunos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Selecione os alunos <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label fw-bold" for="selectAll">
                                                    Selecionar Todos
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <span class="badge bg-primary" id="selectedCount">0 alunos selecionados</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="studentsList" style="max-height: 300px; overflow-y: auto;">
                                    <!-- Será preenchido via JavaScript -->
                                </div>
                                @error('students')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lançamento de Notas -->
                        <div class="card mb-4" id="notesSection" style="display: none;">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>
                                    Lançamento de Notas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row" id="notesTableBody">
                                    <!-- Será preenchido via JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-save me-1"></i>
                                        Lançar Notas em Lote
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-primary-custom {
    background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%) !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-primary {
    color: #058D4F !important;
}

.btn-primary {
    background-color: #058D4F;
    border-color: #058D4F;
}

.btn-primary:hover {
    background-color: #046a3f;
    border-color: #046a3f;
}

.btn-primary:disabled {
    background-color: #6c757d;
    border-color: #6c757d;
}

#notesTableBody tr:hover {
    background-color: #f8f9fa;
}

.percentage-display {
    font-weight: bold;
    font-size: 0.9em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectedCountSpan = document.getElementById('selectedCount');
    const notesSection = document.getElementById('notesSection');
    const studentsSection = document.getElementById('studentsSection');
    const notesTableBody = document.getElementById('notesTableBody');
    const submitBtn = document.getElementById('submitBtn');
    const maxGradeInput = document.getElementById('max_grade');
    const schoolSelect = document.getElementById('school_id');
    const classSelect = document.getElementById('class_id');
    const studentsList = document.getElementById('studentsList');

    // Função para atualizar contador de selecionados
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.student-checkbox:checked');
        selectedCountSpan.textContent = `${selected.length} aluno(s) selecionado(s)`;

        if (selected.length > 0) {
            updateNotesTable();
            notesSection.style.display = 'block';
            submitBtn.disabled = false;
        } else {
            notesSection.style.display = 'none';
            submitBtn.disabled = true;
        }
    }

    // Função para carregar turmas por escola
    function loadClassesBySchool(schoolId) {
        if (!schoolId) {
            classSelect.innerHTML = '<option value="">Primeiro selecione uma escola</option>';
            classSelect.disabled = true;
            studentsSection.style.display = 'none';
            return;
        }

        fetch(`{{ route('notes.classes-by-school') }}?school_id=${schoolId}`)
            .then(response => response.json())
            .then(classes => {
                classSelect.innerHTML = '<option value="">Selecione uma turma...</option>';
                classes.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.id;
                    option.textContent = `${classItem.name} - ${classItem.grade}º Ano (${classItem.period})`;
                    classSelect.appendChild(option);
                });
                classSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erro ao carregar turmas:', error);
                classSelect.innerHTML = '<option value="">Erro ao carregar turmas</option>';
            });
    }

    // Função para carregar alunos por turma
    function loadStudentsByClass(classId) {
        if (!classId) {
            studentsList.innerHTML = '';
            studentsSection.style.display = 'none';
            notesSection.style.display = 'none';
            return;
        }

        fetch(`{{ route('notes.students-by-class') }}?class_id=${classId}`)
            .then(response => response.json())
            .then(students => {
                studentsList.innerHTML = '';

                if (students.length === 0) {
                    studentsList.innerHTML = '<div class="col-12 text-center text-muted">Nenhum aluno encontrado nesta turma.</div>';
                    studentsSection.style.display = 'block';
                    notesSection.style.display = 'none';
                    return;
                }

                // Criar checkboxes para todos os alunos
                students.forEach(student => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 col-lg-4 mb-2';
                    col.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input student-checkbox"
                                   type="checkbox"
                                   name="students[]"
                                   value="${student.id}"
                                   id="student_${student.id}"
                                   checked>
                            <label class="form-check-label" for="student_${student.id}">
                                ${student.name} (${student.enrollment})
                            </label>
                        </div>
                    `;
                    studentsList.appendChild(col);
                });

                // Marcar "Selecionar Todos" como checked
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;

                studentsSection.style.display = 'block';

                // Adicionar event listeners aos novos checkboxes
                addStudentCheckboxListeners();

                // Atualizar contador e mostrar tabela de notas
                updateSelectedCount();
            })
            .catch(error => {
                console.error('Erro ao carregar alunos:', error);
                studentsList.innerHTML = '<div class="col-12 text-center text-danger">Erro ao carregar alunos.</div>';
            });
    }

    // Função para adicionar event listeners aos checkboxes de alunos
    function addStudentCheckboxListeners() {
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');

        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Atualizar checkbox "Selecionar Todos"
                const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                const noneChecked = Array.from(studentCheckboxes).every(cb => !cb.checked);

                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;

                updateSelectedCount();
            });
        });
    }

    // Função para atualizar tabela de notas
    function updateNotesTable() {
        const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'))
            .map(cb => parseInt(cb.value));

        notesTableBody.innerHTML = '';

        selectedStudents.forEach(studentId => {
            // Buscar dados do aluno no DOM
            const studentCheckbox = document.querySelector(`input[value="${studentId}"]`);
            const studentLabel = document.querySelector(`label[for="student_${studentId}"]`);
            const studentName = studentLabel ? studentLabel.textContent.split(' (')[0] : `Aluno ${studentId}`;
            const studentEnrollment = studentLabel ? studentLabel.textContent.match(/\(([^)]+)\)/)?.[1] || '' : '';

            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4 mb-4';
            card.innerHTML = `
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            ${studentName}
                        </h6>
                        <small class="text-muted">Matrícula: ${studentEnrollment}</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="grade_${studentId}" class="form-label">Nota Obtida <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control grade-input"
                                       id="grade_${studentId}"
                                       name="notes[${studentId}][grade]"
                                       step="0.01"
                                       min="0"
                                       max="999.99"
                                       required
                                       data-student-id="${studentId}"
                                       placeholder="0,00">
                                <input type="hidden" name="notes[${studentId}][student_id]" value="${studentId}">
                            </div>
                            <div class="col-6">
                                <label for="max_grade_${studentId}" class="form-label">Nota Máxima <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control max-grade-input"
                                       id="max_grade_${studentId}"
                                       name="notes[${studentId}][max_grade]"
                                       step="0.01"
                                       min="0.01"
                                       max="999.99"
                                       required
                                       data-student-id="${studentId}"
                                       value="10.00">
                            </div>
                            <div class="col-6">
                                <label for="weight_${studentId}" class="form-label">Peso da Nota</label>
                                <input type="number"
                                       class="form-control weight-input"
                                       id="weight_${studentId}"
                                       name="notes[${studentId}][weight]"
                                       step="0.01"
                                       min="0.01"
                                       max="10.00"
                                       data-student-id="${studentId}"
                                       value="1.00">
                                <small class="form-text text-muted">Peso para média ponderada (padrão: 1,00)</small>
                            </div>
                            <div class="col-6">
                                <label for="percentage_${studentId}" class="form-label">Percentual</label>
                                <input type="text"
                                       class="form-control percentage-display"
                                       id="percentage_${studentId}"
                                       data-student-id="${studentId}"
                                       value="0.0%"
                                       readonly>
                                <small class="form-text text-muted">Calculado automaticamente</small>
                            </div>
                            <div class="col-12">
                                <label for="observations_${studentId}" class="form-label">Observações</label>
                                <textarea class="form-control"
                                          id="observations_${studentId}"
                                          name="notes[${studentId}][observations]"
                                          rows="2"
                                          placeholder="Observações sobre a avaliação, desempenho do aluno, etc..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            notesTableBody.appendChild(card);
        });

        // Adicionar event listeners para cálculo de porcentagem
        document.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('input', calculatePercentage);
        });

        document.querySelectorAll('.max-grade-input').forEach(input => {
            input.addEventListener('input', calculatePercentage);
        });
    }

    // Função para calcular porcentagem
    function calculatePercentage(event) {
        const studentId = event.target.dataset.studentId;
        const gradeInput = document.querySelector(`#grade_${studentId}`);
        const maxGradeInput = document.querySelector(`#max_grade_${studentId}`);
        const percentageInput = document.querySelector(`#percentage_${studentId}`);

        const grade = parseFloat(gradeInput.value) || 0;
        const maxGrade = parseFloat(maxGradeInput.value) || 1;
        const percentage = maxGrade > 0 ? (grade / maxGrade) * 100 : 0;

        if (percentageInput) {
            percentageInput.value = `${percentage.toFixed(1)}%`;

            // Colorir baseado na porcentagem
            percentageInput.className = 'form-control percentage-display';
            if (percentage >= 90) percentageInput.classList.add('text-success');
            else if (percentage >= 80) percentageInput.classList.add('text-info');
            else if (percentage >= 70) percentageInput.classList.add('text-primary');
            else if (percentage >= 60) percentageInput.classList.add('text-warning');
            else percentageInput.classList.add('text-danger');
        }
    }

    // Event listeners
    selectAllCheckbox.addEventListener('change', function() {
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Event listener para seleção de escola
    schoolSelect.addEventListener('change', function() {
        const schoolId = this.value;
        loadClassesBySchool(schoolId);
        studentsSection.style.display = 'none';
        notesSection.style.display = 'none';
    });

    // Event listener para seleção de turma
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        loadStudentsByClass(classId);
        notesSection.style.display = 'none';
    });

    maxGradeInput.addEventListener('input', function() {
        // Recalcular todas as porcentagens quando a nota máxima mudar
        document.querySelectorAll('.grade-input').forEach(input => {
            calculatePercentage({ target: input });
        });
    });

    // Validação do formulário
    document.getElementById('batchNotesForm').addEventListener('submit', function(e) {
        // Validar seleção de escola
        if (!schoolSelect.value) {
            e.preventDefault();
            alert('Selecione uma escola.');
            schoolSelect.focus();
            return;
        }

        // Validar seleção de turma
        if (!classSelect.value) {
            e.preventDefault();
            alert('Selecione uma turma.');
            classSelect.focus();
            return;
        }

        // Validar seleção de alunos
        const selectedStudents = document.querySelectorAll('.student-checkbox:checked');
        if (selectedStudents.length === 0) {
            e.preventDefault();
            alert('Selecione pelo menos um aluno.');
            return;
        }

        // Validar notas
        const gradeInputs = document.querySelectorAll('.grade-input');
        const maxGradeInputs = document.querySelectorAll('.max-grade-input');
        let hasEmptyGrades = false;
        let hasEmptyMaxGrades = false;

        gradeInputs.forEach(input => {
            if (!input.value || input.value.trim() === '') {
                hasEmptyGrades = true;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        maxGradeInputs.forEach(input => {
            if (!input.value || input.value.trim() === '') {
                hasEmptyMaxGrades = true;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (hasEmptyGrades || hasEmptyMaxGrades) {
            e.preventDefault();
            alert('Preencha todas as notas e notas máximas dos alunos selecionados.');
            return;
        }
    });

    // Inicializar contador
    updateSelectedCount();
});
</script>
@endsection
