@extends('layouts.admin')

@section('title', 'Nova Nota')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Nova Nota</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-plus me-2"></i>
                            Cadastrar Nova Nota
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

                    <form method="POST" action="{{ route('notes.store') }}">
                        @csrf

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
                                                            {{ (old('student_id') == $student->id || ($selectedStudent && $selectedStudent->id == $student->id)) ? 'selected' : '' }}>
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
                                                    <option value="{{ $key }}" {{ old('subject') == $key ? 'selected' : '' }}>
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
                                                    <option value="{{ $key }}" {{ old('period') == $key ? 'selected' : '' }}>
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
                                                    <option value="{{ $key }}" {{ old('evaluation_type') == $key ? 'selected' : '' }}>
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
                                            <label for="class" class="form-label">Turma</label>
                                            <input type="text"
                                                   class="form-control @error('class') is-invalid @enderror"
                                                   id="class"
                                                   name="class"
                                                   value="{{ old('class') }}"
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
                                                   value="{{ old('grade') }}"
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
                                                   value="{{ old('max_grade', '10.00') }}"
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
                                                   value="{{ old('weight', '1.00') }}"
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
                                                   placeholder="0%">
                                            <small class="form-text text-muted">
                                                Calculado automaticamente
                                            </small>
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
                                                      rows="3"
                                                      placeholder="Observações sobre a avaliação, desempenho do aluno, etc...">{{ old('observations') }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Nota
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

    // Buscar dados do aluno quando selecionado
    document.getElementById('student_id').addEventListener('change', function() {
        const studentId = this.value;
        if (studentId) {
            // Aqui poderia buscar dados do aluno via AJAX se necessário
            // Por exemplo, turma atual, ano letivo, etc.
        }
    });

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
