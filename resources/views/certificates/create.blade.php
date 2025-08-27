@extends('layouts.admin')

@section('title', 'Novo Certificado')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Certificados</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Novo Certificado</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            Gerar Certificado
                        </h4>
                        <a href="{{ route('certificates.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Corrija os erros abaixo:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('certificates.store') }}">
                        @csrf

                        <!-- Seleção da Escola -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-school me-2"></i>
                                    Dados da Escola
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="school_id" class="form-label">Escola *</label>
                                        <select class="form-select" id="school_id" name="school_id" required>
                                            <option value="">Selecione uma escola</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="student_id" class="form-label">Aluno *</label>
                                        <select class="form-select" id="student_id" name="student_id" required>
                                            <option value="">Selecione um aluno formado</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} - {{ $student->enrollment }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados do Certificado -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-certificate me-2"></i>
                                    Dados do Certificado
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="certificate_type" class="form-label">Tipo *</label>
                                        <select class="form-select" id="certificate_type" name="certificate_type" required>
                                            <option value="">Selecione o tipo</option>
                                            <option value="conclusao" {{ old('certificate_type') == 'conclusao' ? 'selected' : '' }}>Certificado de Conclusão</option>
                                            <option value="historico" {{ old('certificate_type') == 'historico' ? 'selected' : '' }}>Histórico Escolar</option>
                                            <option value="declaracao" {{ old('certificate_type') == 'declaracao' ? 'selected' : '' }}>Declaração</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="course_level" class="form-label">Nível de Ensino *</label>
                                        <select class="form-select" id="course_level" name="course_level" required>
                                            <option value="">Selecione o nível</option>
                                            <option value="ensino_medio" {{ old('course_level') == 'ensino_medio' ? 'selected' : '' }}>Ensino Médio</option>
                                            <option value="ensino_fundamental" {{ old('course_level') == 'ensino_fundamental' ? 'selected' : '' }}>Ensino Fundamental</option>
                                            <option value="educacao_infantil" {{ old('course_level') == 'educacao_infantil' ? 'selected' : '' }}>Educação Infantil</option>
                                            <option value="ensino_tecnico" {{ old('course_level') == 'ensino_tecnico' ? 'selected' : '' }}>Ensino Técnico</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="completion_year" class="form-label">Ano de Conclusão *</label>
                                        <input type="number"
                                               class="form-control"
                                               id="completion_year"
                                               name="completion_year"
                                               value="{{ old('completion_year', date('Y')) }}"
                                               min="1990"
                                               max="2030"
                                               required>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="completion_date" class="form-label">Data de Conclusão *</label>
                                        <input type="date"
                                               class="form-control"
                                               id="completion_date"
                                               name="completion_date"
                                               value="{{ old('completion_date') }}"
                                               required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="course_name" class="form-label">Nome do Curso (opcional)</label>
                                        <input type="text"
                                               class="form-control"
                                               id="course_name"
                                               name="course_name"
                                               value="{{ old('course_name') }}"
                                               placeholder="Ex: Técnico em Informática">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="student_birth_place" class="form-label">Naturalidade *</label>
                                        <input type="text"
                                               class="form-control"
                                               id="student_birth_place"
                                               name="student_birth_place"
                                               value="{{ old('student_birth_place') }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados dos Pais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Dados dos Pais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="father_name" class="form-label">Nome do Pai (opcional)</label>
                                        <input type="text"
                                               class="form-control"
                                               id="father_name"
                                               name="father_name"
                                               value="{{ old('father_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mother_name" class="form-label">Nome da Mãe *</label>
                                        <input type="text"
                                               class="form-control"
                                               id="mother_name"
                                               name="mother_name"
                                               value="{{ old('mother_name') }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados das Autoridades -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Autoridades Signatárias
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="director_name" class="form-label">Nome do Diretor *</label>
                                        <input type="text"
                                               class="form-control"
                                               id="director_name"
                                               name="director_name"
                                               value="{{ old('director_name') }}"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="director_title" class="form-label">Cargo do Diretor</label>
                                        <input type="text"
                                               class="form-control"
                                               id="director_title"
                                               name="director_title"
                                               value="{{ old('director_title', 'DIRETOR(A)') }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="secretary_name" class="form-label">Nome do Secretário (opcional)</label>
                                        <input type="text"
                                               class="form-control"
                                               id="secretary_name"
                                               name="secretary_name"
                                               value="{{ old('secretary_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="secretary_title" class="form-label">Cargo do Secretário</label>
                                        <input type="text"
                                               class="form-control"
                                               id="secretary_title"
                                               name="secretary_title"
                                               value="{{ old('secretary_title', 'SECRETÁRIO(A)') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados da Escola -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Dados Adicionais da Escola
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="school_authorization" class="form-label">Autorização de Funcionamento</label>
                                        <input type="text"
                                               class="form-control"
                                               id="school_authorization"
                                               name="school_authorization"
                                               value="{{ old('school_authorization') }}"
                                               placeholder="Ex: 224/2022">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="school_inep" class="form-label">Código INEP</label>
                                        <input type="text"
                                               class="form-control"
                                               id="school_inep"
                                               name="school_inep"
                                               value="{{ old('school_inep') }}"
                                               placeholder="Ex: 22136703">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="observations" class="form-label">Observações</label>
                                        <textarea class="form-control"
                                                  id="observations"
                                                  name="observations"
                                                  rows="3"
                                                  placeholder="Observações adicionais (opcional)">{{ old('observations') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="text-center">
                            <a href="{{ route('certificates.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-certificate me-1"></i>
                                Gerar Certificado
                            </button>
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
    const schoolSelect = document.getElementById('school_id');
    const studentSelect = document.getElementById('student_id');

    schoolSelect.addEventListener('change', function() {
        const schoolId = this.value;

        // Limpar opções de alunos
        studentSelect.innerHTML = '<option value="">Carregando...</option>';

        if (schoolId) {
            fetch(`/api/schools/${schoolId}/students-graduated`)
                .then(response => response.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">Selecione um aluno formado</option>';
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.name} - ${student.enrollment}`;
                        studentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    studentSelect.innerHTML = '<option value="">Erro ao carregar alunos</option>';
                });
        } else {
            studentSelect.innerHTML = '<option value="">Selecione uma escola primeiro</option>';
        }
    });
});
</script>
@endpush
@endsection
