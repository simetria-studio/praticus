@extends('layouts.admin')

@section('title', 'Editar Nota')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Editar Nota</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Editar Nota
                            <span class="badge {{ $note->performance_class }} ms-2">{{ $note->performance_level }}</span>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('notes.show', $note) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Visualizar
                            </a>
                            <a href="{{ route('notes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
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

                    <!-- Informações do Aluno -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-graduate me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1">{{ $note->student->name }}</h6>
                                <small>Matrícula: {{ $note->student->enrollment }} | Série: {{ $note->student->grade_with_class }}</small>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('notes.update', $note) }}">
                        @csrf
                        @method('PUT')

                        <!-- Dados da Avaliação -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Dados da Avaliação
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">Aluno <span class="text-danger">*</span></label>
                                            <select class="form-select @error('student_id') is-invalid @enderror"
                                                    id="student_id"
                                                    name="student_id"
                                                    required>
                                                <option value="">Selecione um aluno...</option>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->id }}"
                                                            {{ (old('student_id', $note->student_id) == $student->id) ? 'selected' : '' }}>
                                                        {{ $student->name }} - {{ $student->enrollment }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('student_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Disciplina <span class="text-danger">*</span></label>
                                            <select class="form-select @error('subject') is-invalid @enderror"
                                                    id="subject"
                                                    name="subject"
                                                    required>
                                                <option value="">Selecione...</option>
                                                @foreach($subjects as $key => $subject)
                                                    <option value="{{ $key }}" {{ old('subject', $note->subject) == $key ? 'selected' : '' }}>
                                                        {{ $subject }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="period" class="form-label">Período <span class="text-danger">*</span></label>
                                            <select class="form-select @error('period') is-invalid @enderror"
                                                    id="period"
                                                    name="period"
                                                    required>
                                                <option value="">Selecione...</option>
                                                @foreach($periods as $key => $period)
                                                    <option value="{{ $key }}" {{ old('period', $note->period) == $key ? 'selected' : '' }}>
                                                        {{ $period }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="evaluation_type" class="form-label">Tipo de Avaliação <span class="text-danger">*</span></label>
                                            <select class="form-select @error('evaluation_type') is-invalid @enderror"
                                                    id="evaluation_type"
                                                    name="evaluation_type"
                                                    required>
                                                <option value="">Selecione...</option>
                                                @foreach($evaluationTypes as $key => $type)
                                                    <option value="{{ $key }}" {{ old('evaluation_type', $note->evaluation_type) == $key ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evaluation_type')
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
                                                   value="{{ old('evaluation_date', $note->evaluation_date->format('Y-m-d')) }}"
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
                                                   value="{{ old('school_year', $note->school_year) }}"
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
                                            <label for="class" class="form-label">Turma</label>
                                            <input type="text"
                                                   class="form-control @error('class') is-invalid @enderror"
                                                   id="class"
                                                   name="class"
                                                   value="{{ old('class', $note->class) }}"
                                                   placeholder="Ex: A, B, 1A">
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nota e Peso -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>
                                    Nota e Avaliação
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="grade" class="form-label">Nota Obtida <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('grade') is-invalid @enderror"
                                                   id="grade"
                                                   name="grade"
                                                   value="{{ old('grade', number_format($note->grade, 2, '.', '')) }}"
                                                   min="0"
                                                   max="999.99"
                                                   step="0.01"
                                                   placeholder="0,00"
                                                   required>
                                            @error('grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="grade-conversion-info" class="mt-2" style="display: none;">
                                                <div class="alert alert-info py-2 px-3 mb-0" role="alert">
                                                    <i class="fas fa-magic me-1"></i>
                                                    <span id="conversion-message"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="max_grade" class="form-label">Nota Máxima <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('max_grade') is-invalid @enderror"
                                                   id="max_grade"
                                                   name="max_grade"
                                                   value="{{ old('max_grade', number_format($note->max_grade, 2, '.', '')) }}"
                                                   min="0.01"
                                                   max="999.99"
                                                   step="0.01"
                                                   placeholder="10,00"
                                                   required>
                                            @error('max_grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Peso da Nota</label>
                                            <input type="number"
                                                   class="form-control @error('weight') is-invalid @enderror"
                                                   id="weight"
                                                   name="weight"
                                                   value="{{ old('weight', number_format($note->weight, 2, '.', '')) }}"
                                                   min="0.01"
                                                   max="10.00"
                                                   step="0.01"
                                                   placeholder="1,00">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Peso para média ponderada (padrão: 1,00)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="percentage" class="form-label">Percentual</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="percentage"
                                                   readonly
                                                   value="{{ number_format($note->percentage, 1, ',', '.') }}%">
                                            <small class="form-text text-muted">
                                                Calculado automaticamente
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="mb-3">
                                            <label for="observations" class="form-label">Observações</label>
                                            <textarea class="form-control @error('observations') is-invalid @enderror"
                                                      id="observations"
                                                      name="observations"
                                                      rows="3"
                                                      placeholder="Observações sobre a avaliação, desempenho do aluno, etc...">{{ old('observations', $note->observations) }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status"
                                                    name="status"
                                                    required>
                                                <option value="ativa" {{ old('status', $note->status) == 'ativa' ? 'selected' : '' }}>Ativa</option>
                                                <option value="cancelada" {{ old('status', $note->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                <option value="corrigida" {{ old('status', $note->status) == 'corrigida' ? 'selected' : '' }}>Corrigida</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Histórico da Nota -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Histórico da Nota
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Criada em</label>
                                            <p class="fw-bold">{{ $note->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Última atualização</label>
                                            <p class="fw-bold">{{ $note->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($note->created_by || $note->updated_by)
                                    <div class="row">
                                        @if($note->created_by)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Criada por</label>
                                                    <p class="fw-bold">{{ $note->created_by }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($note->updated_by)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Atualizada por</label>
                                                    <p class="fw-bold">{{ $note->updated_by }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('notes.show', $note) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Atualizar Nota
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeInput = document.getElementById('grade');
    const maxGradeInput = document.getElementById('max_grade');
    const percentageInput = document.getElementById('percentage');
    const conversionInfo = document.getElementById('grade-conversion-info');
    const conversionMessage = document.getElementById('conversion-message');

    // Função para converter nota se necessário
    function convertGradeIfNeeded(grade, maxGrade) {
        const originalGrade = grade;
        let convertedGrade = grade;
        let message = null;

        if (grade > maxGrade) {
            let attempts = 0;
            const maxAttempts = 3;

            while (convertedGrade > maxGrade && attempts < maxAttempts) {
                convertedGrade = convertedGrade / 10;
                attempts++;
            }

            if (convertedGrade > maxGrade) {
                convertedGrade = maxGrade;
                message = `A nota será ajustada de ${originalGrade} para ${convertedGrade.toFixed(2)} (nota máxima)`;
            } else {
                message = `A nota será convertida de ${originalGrade} para ${convertedGrade.toFixed(2)}`;
            }
        }

        return {
            grade: Math.round(convertedGrade * 100) / 100,
            message: message
        };
    }

    // Calcular percentual e mostrar conversão
    function calculatePercentageAndShowConversion() {
        const grade = parseFloat(gradeInput.value) || 0;
        const maxGrade = parseFloat(maxGradeInput.value) || 0;

        if (maxGrade > 0) {
            // Verificar se precisa converter
            const conversion = convertGradeIfNeeded(grade, maxGrade);

            if (conversion.message) {
                // Mostrar informação de conversão
                conversionMessage.textContent = conversion.message;
                conversionInfo.style.display = 'block';

                // Calcular percentual com a nota convertida
                const percentage = (conversion.grade / maxGrade * 100).toFixed(1);
                percentageInput.value = percentage + '%';
            } else {
                // Esconder informação de conversão
                conversionInfo.style.display = 'none';

                // Calcular percentual normal
                const percentage = (grade / maxGrade * 100).toFixed(1);
                percentageInput.value = percentage + '%';
            }
        } else {
            conversionInfo.style.display = 'none';
            percentageInput.value = '0%';
        }
    }

    // Adicionar eventos
    gradeInput.addEventListener('input', calculatePercentageAndShowConversion);
    maxGradeInput.addEventListener('input', calculatePercentageAndShowConversion);

    // Calcular percentual inicial se houver valores
    calculatePercentageAndShowConversion();

    // Formatação de números
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        if (input.step === '0.01') {
            input.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                    // Recalcular após formatação
                    if (this.id === 'grade' || this.id === 'max_grade') {
                        calculatePercentageAndShowConversion();
                    }
                }
            });
        }
    });

    // Confirmação ao alterar status para cancelada
    document.getElementById('status').addEventListener('change', function() {
        if (this.value === 'cancelada') {
            if (!confirm('Tem certeza que deseja cancelar esta nota? Esta ação pode afetar a média do aluno.')) {
                this.value = '{{ $note->status }}'; // Volta ao valor original
            }
        }
    });

    // Animação suave para mostrar/esconder o alerta de conversão
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                const target = mutation.target;
                if (target.id === 'grade-conversion-info') {
                    if (target.style.display === 'block') {
                        target.style.opacity = '0';
                        target.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            target.style.transition = 'all 0.3s ease';
                            target.style.opacity = '1';
                            target.style.transform = 'translateY(0)';
                        }, 10);
                    }
                }
            }
        });
    });

    observer.observe(conversionInfo, { attributes: true });
});
</script>
@endpush
@endsection
