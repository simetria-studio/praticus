@extends('layouts.admin')

@section('title', 'Editar Aluno')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Editar Aluno</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Aluno: {{ $student->name }}
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Visualizar
                            </a>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
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

                    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Dados Pessoais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    Dados Pessoais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nome Completo *</label>
                                        <input type="text"
                                               class="form-control"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $student->name) }}"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="email"
                                               class="form-control"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $student->email) }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="phone" class="form-label">Telefone</label>
                                        <input type="text"
                                               class="form-control phone-mask"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone', $student->phone) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cpf" class="form-label">CPF</label>
                                        <input type="text"
                                               class="form-control cpf-mask"
                                               id="cpf"
                                               name="cpf"
                                               value="{{ old('cpf', $student->cpf) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rg" class="form-label">RG</label>
                                        <input type="text"
                                               class="form-control"
                                               id="rg"
                                               name="rg"
                                               value="{{ old('rg', $student->rg) }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="birth_date" class="form-label">Data de Nascimento</label>
                                        <input type="date"
                                               class="form-control"
                                               id="birth_date"
                                               name="birth_date"
                                               value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="gender" class="form-label">Sexo</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">Selecione</option>
                                            <option value="masculino" {{ old('gender', $student->gender) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="feminino" {{ old('gender', $student->gender) == 'feminino' ? 'selected' : '' }}>Feminino</option>
                                            <option value="outro" {{ old('gender', $student->gender) == 'outro' ? 'selected' : '' }}>Outro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="photo" class="form-label">Foto</label>
                                        <input type="file"
                                               class="form-control"
                                               id="photo"
                                               name="photo"
                                               accept="image/*">
                                        @if($student->photo)
                                            <small class="text-muted">Foto atual: {{ basename($student->photo) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Acadêmicos -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Dados Acadêmicos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="enrollment" class="form-label">Matrícula *</label>
                                        <input type="text"
                                               class="form-control"
                                               id="enrollment"
                                               name="enrollment"
                                               value="{{ old('enrollment', $student->enrollment) }}"
                                               required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="school_id" class="form-label">Escola *</label>
                                        <select class="form-select" id="school_id" name="school_id" required>
                                            <option value="">Selecione uma escola</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="grade" class="form-label">Série/Ano</label>
                                        <input type="text"
                                               class="form-control"
                                               id="grade"
                                               name="grade"
                                               value="{{ old('grade', $student->grade) }}"
                                               placeholder="Ex: 1º Ano">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="class_id" class="form-label">Turma (selecionar)</label>
                                        <select class="form-select" id="class_id" name="class_id">
                                            <option value="">Sem turma</option>
                                            @isset($classes)
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }} - {{ $class->year }} ({{ $class->school->name }})
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="class" class="form-label">Turma</label>
                                        <input type="text"
                                               class="form-control"
                                               id="class"
                                               name="class"
                                               value="{{ old('class', $student->class) }}"
                                               placeholder="Ex: A, B, C">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="school_year" class="form-label">Ano Letivo</label>
                                        <input type="number"
                                               class="form-control"
                                               id="school_year"
                                               name="school_year"
                                               value="{{ old('school_year', $student->school_year) }}"
                                               min="2020"
                                               max="2030">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status"
                                                    name="status"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="ativo" {{ old('status', $student->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="inativo" {{ old('status', $student->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                                <option value="transferido" {{ old('status', $student->status) == 'transferido' ? 'selected' : '' }}>Transferido</option>
                                                <option value="formado" {{ old('status', $student->status) == 'formado' ? 'selected' : '' }}>Formado</option>
                                                <option value="evadido" {{ old('status', $student->status) == 'evadido' ? 'selected' : '' }}>Evadido</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Endereço
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                                                        <div class="col-md-3">
                                        <label for="postal_code" class="form-label">CEP</label>
                                        <input type="text"
                                               class="form-control cep-mask"
                                               id="postal_code"
                                               name="postal_code"
                                               value="{{ old('postal_code', $student->postal_code) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="street" class="form-label">Logradouro</label>
                                        <input type="text"
                                               class="form-control"
                                               id="street"
                                               name="street"
                                               value="{{ old('street', $student->street) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="number" class="form-label">Número</label>
                                        <input type="text"
                                               class="form-control"
                                               id="number"
                                               name="number"
                                               value="{{ old('number', $student->number) }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input type="text"
                                               class="form-control"
                                               id="complement"
                                               name="complement"
                                               value="{{ old('complement', $student->complement) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="neighborhood" class="form-label">Bairro</label>
                                        <input type="text"
                                               class="form-control"
                                               id="neighborhood"
                                               name="neighborhood"
                                               value="{{ old('neighborhood', $student->neighborhood) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">Cidade</label>
                                        <input type="text"
                                               class="form-control"
                                               id="city"
                                               name="city"
                                               value="{{ old('city', $student->city) }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="state" class="form-label">Estado</label>
                                        <select class="form-select" id="state" name="state">
                                            <option value="">Selecione</option>
                                            <option value="AC" {{ old('state', $student->state) == 'AC' ? 'selected' : '' }}>Acre</option>
                                            <option value="AL" {{ old('state', $student->state) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                            <option value="AP" {{ old('state', $student->state) == 'AP' ? 'selected' : '' }}>Amapá</option>
                                            <option value="AM" {{ old('state', $student->state) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                            <option value="BA" {{ old('state', $student->state) == 'BA' ? 'selected' : '' }}>Bahia</option>
                                            <option value="CE" {{ old('state', $student->state) == 'CE' ? 'selected' : '' }}>Ceará</option>
                                            <option value="DF" {{ old('state', $student->state) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                            <option value="ES" {{ old('state', $student->state) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                            <option value="GO" {{ old('state', $student->state) == 'GO' ? 'selected' : '' }}>Goiás</option>
                                            <option value="MA" {{ old('state', $student->state) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                            <option value="MT" {{ old('state', $student->state) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                            <option value="MS" {{ old('state', $student->state) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            <option value="MG" {{ old('state', $student->state) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                            <option value="PA" {{ old('state', $student->state) == 'PA' ? 'selected' : '' }}>Pará</option>
                                            <option value="PB" {{ old('state', $student->state) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                            <option value="PR" {{ old('state', $student->state) == 'PR' ? 'selected' : '' }}>Paraná</option>
                                            <option value="PE" {{ old('state', $student->state) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                            <option value="PI" {{ old('state', $student->state) == 'PI' ? 'selected' : '' }}>Piauí</option>
                                            <option value="RJ" {{ old('state', $student->state) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                            <option value="RN" {{ old('state', $student->state) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                            <option value="RS" {{ old('state', $student->state) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            <option value="RO" {{ old('state', $student->state) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                            <option value="RR" {{ old('state', $student->state) == 'RR' ? 'selected' : '' }}>Roraima</option>
                                            <option value="SC" {{ old('state', $student->state) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                            <option value="SP" {{ old('state', $student->state) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                            <option value="SE" {{ old('state', $student->state) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                            <option value="TO" {{ old('state', $student->state) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country" class="form-label">País</label>
                                        <input type="text"
                                               class="form-control"
                                               id="country"
                                               name="country"
                                               value="{{ old('country', $student->country ?? 'Brasil') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados do Responsável -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Dados do Responsável
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="guardian_name" class="form-label">Nome do Responsável</label>
                                        <input type="text"
                                               class="form-control"
                                               id="guardian_name"
                                               name="guardian_name"
                                               value="{{ old('guardian_name', $student->guardian_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guardian_phone" class="form-label">Telefone do Responsável</label>
                                        <input type="text"
                                               class="form-control phone-mask"
                                               id="guardian_phone"
                                               name="guardian_phone"
                                               value="{{ old('guardian_phone', $student->guardian_phone) }}">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="guardian_email" class="form-label">E-mail do Responsável</label>
                                        <input type="email"
                                               class="form-control"
                                               id="guardian_email"
                                               name="guardian_email"
                                               value="{{ old('guardian_email', $student->guardian_email) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="guardian_cpf" class="form-label">CPF do Responsável</label>
                                        <input type="text"
                                               class="form-control cpf-mask"
                                               id="guardian_cpf"
                                               name="guardian_cpf"
                                               value="{{ old('guardian_cpf', $student->guardian_cpf) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="guardian_relationship" class="form-label">Parentesco</label>
                                        <select class="form-select" id="guardian_relationship" name="guardian_relationship">
                                            <option value="">Selecione</option>
                                            <option value="pai" {{ old('guardian_relationship', $student->guardian_relationship) == 'pai' ? 'selected' : '' }}>Pai</option>
                                            <option value="mae" {{ old('guardian_relationship', $student->guardian_relationship) == 'mae' ? 'selected' : '' }}>Mãe</option>
                                            <option value="avo" {{ old('guardian_relationship', $student->guardian_relationship) == 'avo' ? 'selected' : '' }}>Avô(ó)</option>
                                            <option value="tio" {{ old('guardian_relationship', $student->guardian_relationship) == 'tio' ? 'selected' : '' }}>Tio(a)</option>
                                            <option value="tutor" {{ old('guardian_relationship', $student->guardian_relationship) == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                            <option value="outro" {{ old('guardian_relationship', $student->guardian_relationship) == 'outro' ? 'selected' : '' }}>Outro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Informações Adicionais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="medical_info" class="form-label">Informações Médicas</label>
                                        <textarea class="form-control"
                                                  id="medical_info"
                                                  name="medical_info"
                                                  rows="3"
                                                  placeholder="Alergias, medicamentos, necessidades especiais...">{{ old('medical_info', $student->medical_info) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="notes" class="form-label">Observações</label>
                                        <textarea class="form-control"
                                                  id="notes"
                                                  name="notes"
                                                  rows="3"
                                                  placeholder="Observações gerais sobre o aluno...">{{ old('notes', $student->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="text-center">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>
                                Salvar Alterações
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
    // Máscara para telefone
    const phoneMasks = document.querySelectorAll('.phone-mask');
    phoneMasks.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            e.target.value = value;
        });
    });

    // Máscara para CPF
    const cpfMasks = document.querySelectorAll('.cpf-mask');
    cpfMasks.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            e.target.value = value;
        });
    });

    // Máscara para CEP
    const cepMasks = document.querySelectorAll('.cep-mask');
    cepMasks.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });

        // Buscar endereço pelo CEP
        input.addEventListener('blur', function(e) {
            const cep = e.target.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('street').value = data.logradouro || '';
                            document.getElementById('neighborhood').value = data.bairro || '';
                            document.getElementById('city').value = data.localidade || '';
                            document.getElementById('state').value = data.uf || '';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                    });
            }
        });
    });

    // Filtrar turmas baseado na escola selecionada
    const schoolSelect = document.getElementById('school_id');
    const classSelect = document.getElementById('class_id');

    if (schoolSelect && classSelect) {
        schoolSelect.addEventListener('change', function() {
            const schoolId = this.value;
            const currentClassId = '{{ $student->class_id }}';

            // Limpar turmas selecionadas
            classSelect.innerHTML = '<option value="">Sem turma</option>';

            if (schoolId) {
                // Buscar turmas da escola selecionada
                fetch(`/api/schools/${schoolId}/classes`)
                    .then(response => response.json())
                    .then(classes => {
                        classes.forEach(classItem => {
                            const option = document.createElement('option');
                            option.value = classItem.id;
                            option.textContent = `${classItem.name} - ${classItem.year} (${classItem.grade})`;

                            // Manter selecionada a turma atual se ainda pertencer à escola
                            if (classItem.id == currentClassId) {
                                option.selected = true;
                            }

                            classSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao buscar turmas:', error);
                    });
            }
        });

        // Carregar turmas da escola atual ao carregar a página
        if (schoolSelect.value) {
            schoolSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush
@endsection
