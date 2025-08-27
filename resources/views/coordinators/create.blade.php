@extends('layouts.admin')

@section('title', 'Novo Coordenador')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('coordinators.index') }}">Coordenadores</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Novo Coordenador</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-tie me-2"></i>
                            Cadastrar Novo Coordenador
                        </h4>
                        <a href="{{ route('coordinators.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
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

                    <form method="POST" action="{{ route('coordinators.store') }}" enctype="multipart/form-data">
                        @csrf

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
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Telefone</label>
                                            <input type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   placeholder="(11) 99999-9999">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cpf" class="form-label">CPF</label>
                                            <input type="text"
                                                   class="form-control @error('cpf') is-invalid @enderror"
                                                   id="cpf"
                                                   name="cpf"
                                                   value="{{ old('cpf') }}"
                                                   placeholder="000.000.000-00">
                                            @error('cpf')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Foto</label>
                                            <input type="file"
                                                   class="form-control @error('photo') is-invalid @enderror"
                                                   id="photo"
                                                   name="photo"
                                                   accept="image/*">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Profissionais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-briefcase me-2"></i>
                                    Dados Profissionais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="registration" class="form-label">Matrícula Funcional <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('registration') is-invalid @enderror"
                                                   id="registration"
                                                   name="registration"
                                                   value="{{ old('registration') }}"
                                                   required>
                                            @error('registration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="specialty" class="form-label">Especialidade</label>
                                            <input type="text"
                                                   class="form-control @error('specialty') is-invalid @enderror"
                                                   id="specialty"
                                                   name="specialty"
                                                   value="{{ old('specialty') }}"
                                                   placeholder="Ex: Pedagogia, Administração Escolar">
                                            @error('specialty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="school_id" class="form-label">Escola <span class="text-danger">*</span></label>
                                            <select class="form-select @error('school_id') is-invalid @enderror"
                                                    id="school_id"
                                                    name="school_id"
                                                    required>
                                                <option value="">Selecione uma escola...</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }} - {{ $school->city }}/{{ $school->state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="degree" class="form-label">Formação Acadêmica</label>
                                            <input type="text"
                                                   class="form-control @error('degree') is-invalid @enderror"
                                                   id="degree"
                                                   name="degree"
                                                   value="{{ old('degree') }}"
                                                   placeholder="Ex: Licenciatura em Pedagogia">
                                            @error('degree')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="institution" class="form-label">Instituição de Formação</label>
                                            <input type="text"
                                                   class="form-control @error('institution') is-invalid @enderror"
                                                   id="institution"
                                                   name="institution"
                                                   value="{{ old('institution') }}">
                                            @error('institution')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="graduation_year" class="form-label">Ano de Formação</label>
                                            <input type="number"
                                                   class="form-control @error('graduation_year') is-invalid @enderror"
                                                   id="graduation_year"
                                                   name="graduation_year"
                                                   value="{{ old('graduation_year') }}"
                                                   min="1900"
                                                   max="{{ date('Y') + 1 }}">
                                            @error('graduation_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="hiring_date" class="form-label">Data de Contratação</label>
                                            <input type="date"
                                                   class="form-control @error('hiring_date') is-invalid @enderror"
                                                   id="hiring_date"
                                                   name="hiring_date"
                                                   value="{{ old('hiring_date') }}">
                                            @error('hiring_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="contract_type" class="form-label">Tipo de Contrato</label>
                                            <select class="form-select @error('contract_type') is-invalid @enderror"
                                                    id="contract_type"
                                                    name="contract_type">
                                                <option value="">Selecione...</option>
                                                <option value="efetivo" {{ old('contract_type') == 'efetivo' ? 'selected' : '' }}>Efetivo</option>
                                                <option value="temporario" {{ old('contract_type') == 'temporario' ? 'selected' : '' }}>Temporário</option>
                                                <option value="contratado" {{ old('contract_type') == 'contratado' ? 'selected' : '' }}>Contratado</option>
                                                <option value="estagiario" {{ old('contract_type') == 'estagiario' ? 'selected' : '' }}>Estagiário</option>
                                            </select>
                                            @error('contract_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status"
                                                    name="status"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="salary" class="form-label">Salário</label>
                                            <input type="number"
                                                   class="form-control @error('salary') is-invalid @enderror"
                                                   id="salary"
                                                   name="salary"
                                                   value="{{ old('salary') }}"
                                                   step="0.01"
                                                   min="0"
                                                   placeholder="0.00">
                                            @error('salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="workload" class="form-label">Carga Horária</label>
                                            <input type="text"
                                                   class="form-control @error('workload') is-invalid @enderror"
                                                   id="workload"
                                                   name="workload"
                                                   value="{{ old('workload') }}"
                                                   placeholder="Ex: 40h semanais">
                                            @error('workload')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Áreas de Coordenação -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Áreas de Coordenação
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="coordinated_grades" class="form-label">Séries/Anos que Coordena</label>
                                            <input type="text"
                                                   class="form-control @error('coordinated_grades') is-invalid @enderror"
                                                   id="coordinated_grades"
                                                   name="coordinated_grades[]"
                                                   value="{{ old('coordinated_grades.0') }}"
                                                   placeholder="Ex: 1º ano, 2º ano">
                                            <small class="form-text text-muted">Digite as séries separadas por vírgula</small>
                                            @error('coordinated_grades')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="coordinated_subjects" class="form-label">Disciplinas que Coordena</label>
                                            <input type="text"
                                                   class="form-control @error('coordinated_subjects') is-invalid @enderror"
                                                   id="coordinated_subjects"
                                                   name="coordinated_subjects[]"
                                                   value="{{ old('coordinated_subjects.0') }}"
                                                   placeholder="Ex: Matemática, Português">
                                            <small class="form-text text-muted">Digite as disciplinas separadas por vírgula</small>
                                            @error('coordinated_subjects')
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
                                        <div class="mb-3">
                                            <label for="postal_code" class="form-label">CEP</label>
                                            <input type="text"
                                                   class="form-control @error('postal_code') is-invalid @enderror"
                                                   id="postal_code"
                                                   name="postal_code"
                                                   value="{{ old('postal_code') }}"
                                                   placeholder="00000-000">
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="street" class="form-label">Rua/Avenida</label>
                                            <input type="text"
                                                   class="form-control @error('street') is-invalid @enderror"
                                                   id="street"
                                                   name="street"
                                                   value="{{ old('street') }}">
                                            @error('street')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="number" class="form-label">Número</label>
                                            <input type="text"
                                                   class="form-control @error('number') is-invalid @enderror"
                                                   id="number"
                                                   name="number"
                                                   value="{{ old('number') }}">
                                            @error('number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="complement" class="form-label">Complemento</label>
                                            <input type="text"
                                                   class="form-control @error('complement') is-invalid @enderror"
                                                   id="complement"
                                                   name="complement"
                                                   value="{{ old('complement') }}"
                                                   placeholder="Apto, Casa, etc.">
                                            @error('complement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="neighborhood" class="form-label">Bairro</label>
                                            <input type="text"
                                                   class="form-control @error('neighborhood') is-invalid @enderror"
                                                   id="neighborhood"
                                                   name="neighborhood"
                                                   value="{{ old('neighborhood') }}">
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">Cidade</label>
                                            <input type="text"
                                                   class="form-control @error('city') is-invalid @enderror"
                                                   id="city"
                                                   name="city"
                                                   value="{{ old('city') }}">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">Estado</label>
                                            <select class="form-select @error('state') is-invalid @enderror"
                                                    id="state"
                                                    name="state">
                                                <option value="">Selecione...</option>
                                                <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre</option>
                                                <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                                <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                                <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                                <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                                <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                                <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                                <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                                <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                                <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                                <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                                <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                                <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                                <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará</option>
                                                <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                                <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                                <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                                <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                                <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                                <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                                <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                                <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                                <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                                <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                                <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                                <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                                <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                            </select>
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">País</label>
                                            <input type="text"
                                                   class="form-control @error('country') is-invalid @enderror"
                                                   id="country"
                                                   name="country"
                                                   value="{{ old('country', 'Brasil') }}"
                                                   readonly>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informações Adicionais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observações</label>
                                    <textarea class="form-control @error('observations') is-invalid @enderror"
                                              id="observations"
                                              name="observations"
                                              rows="3"
                                              placeholder="Informações adicionais sobre o coordenador...">{{ old('observations') }}</textarea>
                                    @error('observations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('coordinators.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Coordenador
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
    // Máscara para CEP
    const cepInput = document.getElementById('postal_code');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 5) {
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d)/, '$1.$2.$3-$4');
            }
            e.target.value = value;
        });
    }

    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 10) {
                value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
            e.target.value = value;
        });
    }

    // Buscar CEP
    cepInput?.addEventListener('blur', function(e) {
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
</script>
@endpush
@endsection
