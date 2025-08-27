@extends('layouts.admin')

@section('title', 'Novo Aluno')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('students.index') }}">Alunos</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Novo Aluno</span>
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
                            Cadastrar Novo Aluno
                        </h4>
                        <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
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

                    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
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
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="birth_date" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                                            <input type="date"
                                                   class="form-control @error('birth_date') is-invalid @enderror"
                                                   id="birth_date"
                                                   name="birth_date"
                                                   value="{{ old('birth_date') }}"
                                                   required>
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Sexo</label>
                                            <select class="form-select @error('gender') is-invalid @enderror"
                                                    id="gender"
                                                    name="gender">
                                                <option value="">Selecione...</option>
                                                <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option value="feminino" {{ old('gender') == 'feminino' ? 'selected' : '' }}>Feminino</option>
                                                <option value="outro" {{ old('gender') == 'outro' ? 'selected' : '' }}>Outro</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
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
                                            <label for="rg" class="form-label">RG</label>
                                            <input type="text"
                                                   class="form-control @error('rg') is-invalid @enderror"
                                                   id="rg"
                                                   name="rg"
                                                   value="{{ old('rg') }}"
                                                   placeholder="00.000.000-0">
                                            @error('rg')
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="aluno@exemplo.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                        <div class="mb-3">
                                            <label for="enrollment" class="form-label">Matrícula <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('enrollment') is-invalid @enderror"
                                                   id="enrollment"
                                                   name="enrollment"
                                                   value="{{ old('enrollment') }}"
                                                   placeholder="Ex: 2024001"
                                                   required>
                                            @error('enrollment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
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
                                            <label for="grade" class="form-label">Série/Ano <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('grade') is-invalid @enderror"
                                                   id="grade"
                                                   name="grade"
                                                   value="{{ old('grade') }}"
                                                   placeholder="Ex: 1º ano"
                                                   required>
                                            @error('grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="class_id" class="form-label">Turma (selecionar)</label>
                                            <select class="form-select @error('class_id') is-invalid @enderror"
                                                    id="class_id"
                                                    name="class_id">
                                                <option value="">Sem turma</option>
                                                @isset($classes)
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }} - {{ $class->year }} ({{ $class->school->name }})
                                                        </option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('class_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="class" class="form-label">Turma</label>
                                            <input type="text"
                                                   class="form-control @error('class') is-invalid @enderror"
                                                   id="class"
                                                   name="class"
                                                   value="{{ old('class') }}"
                                                   placeholder="Ex: A">
                                            @error('class')
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
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status"
                                                    name="status"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                                <option value="transferido" {{ old('status') == 'transferido' ? 'selected' : '' }}>Transferido</option>
                                                <option value="formado" {{ old('status') == 'formado' ? 'selected' : '' }}>Formado</option>
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
                                            <label for="enrollment_date" class="form-label">Data da Matrícula</label>
                                            <input type="date"
                                                   class="form-control @error('enrollment_date') is-invalid @enderror"
                                                   id="enrollment_date"
                                                   name="enrollment_date"
                                                   value="{{ old('enrollment_date') }}">
                                            @error('enrollment_date')
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
                                            <label for="postal_code" class="form-label">CEP <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('postal_code') is-invalid @enderror"
                                                   id="postal_code"
                                                   name="postal_code"
                                                   value="{{ old('postal_code') }}"
                                                   placeholder="00000-000"
                                                   required>
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="street" class="form-label">Rua/Avenida <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('street') is-invalid @enderror"
                                                   id="street"
                                                   name="street"
                                                   value="{{ old('street') }}"
                                                   required>
                                            @error('street')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="number" class="form-label">Número <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('number') is-invalid @enderror"
                                                   id="number"
                                                   name="number"
                                                   value="{{ old('number') }}"
                                                   required>
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
                                            <label for="neighborhood" class="form-label">Bairro <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('neighborhood') is-invalid @enderror"
                                                   id="neighborhood"
                                                   name="neighborhood"
                                                   value="{{ old('neighborhood') }}"
                                                   required>
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">Cidade <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('city') is-invalid @enderror"
                                                   id="city"
                                                   name="city"
                                                   value="{{ old('city') }}"
                                                   required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">Estado <span class="text-danger">*</span></label>
                                            <select class="form-select @error('state') is-invalid @enderror"
                                                    id="state"
                                                    name="state"
                                                    required>
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
                                        <div class="mb-3">
                                            <label for="guardian_name" class="form-label">Nome do Responsável <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('guardian_name') is-invalid @enderror"
                                                   id="guardian_name"
                                                   name="guardian_name"
                                                   value="{{ old('guardian_name') }}"
                                                   required>
                                            @error('guardian_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="guardian_phone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('guardian_phone') is-invalid @enderror"
                                                   id="guardian_phone"
                                                   name="guardian_phone"
                                                   value="{{ old('guardian_phone') }}"
                                                   placeholder="(11) 99999-9999"
                                                   required>
                                            @error('guardian_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="guardian_relationship" class="form-label">Parentesco <span class="text-danger">*</span></label>
                                            <select class="form-select @error('guardian_relationship') is-invalid @enderror"
                                                    id="guardian_relationship"
                                                    name="guardian_relationship"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="pai" {{ old('guardian_relationship') == 'pai' ? 'selected' : '' }}>Pai</option>
                                                <option value="mae" {{ old('guardian_relationship') == 'mae' ? 'selected' : '' }}>Mãe</option>
                                                <option value="avo" {{ old('guardian_relationship') == 'avo' ? 'selected' : '' }}>Avô</option>
                                                <option value="ava" {{ old('guardian_relationship') == 'ava' ? 'selected' : '' }}>Avó</option>
                                                <option value="tio" {{ old('guardian_relationship') == 'tio' ? 'selected' : '' }}>Tio</option>
                                                <option value="tia" {{ old('guardian_relationship') == 'tia' ? 'selected' : '' }}>Tia</option>
                                                <option value="responsavel_legal" {{ old('guardian_relationship', 'responsavel_legal') == 'responsavel_legal' ? 'selected' : '' }}>Responsável Legal</option>
                                                <option value="outro" {{ old('guardian_relationship') == 'outro' ? 'selected' : '' }}>Outro</option>
                                            </select>
                                            @error('guardian_relationship')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="guardian_email" class="form-label">E-mail do Responsável</label>
                                            <input type="email"
                                                   class="form-control @error('guardian_email') is-invalid @enderror"
                                                   id="guardian_email"
                                                   name="guardian_email"
                                                   value="{{ old('guardian_email') }}"
                                                   placeholder="responsavel@exemplo.com">
                                            @error('guardian_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="guardian_cpf" class="form-label">CPF do Responsável</label>
                                            <input type="text"
                                                   class="form-control @error('guardian_cpf') is-invalid @enderror"
                                                   id="guardian_cpf"
                                                   name="guardian_cpf"
                                                   value="{{ old('guardian_cpf') }}"
                                                   placeholder="000.000.000-00">
                                            @error('guardian_cpf')
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="medical_info" class="form-label">Informações Médicas</label>
                                            <textarea class="form-control @error('medical_info') is-invalid @enderror"
                                                      id="medical_info"
                                                      name="medical_info"
                                                      rows="3"
                                                      placeholder="Alergias, medicamentos, condições especiais...">{{ old('medical_info') }}</textarea>
                                            @error('medical_info')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="observations" class="form-label">Observações Gerais</label>
                                            <textarea class="form-control @error('observations') is-invalid @enderror"
                                                      id="observations"
                                                      name="observations"
                                                      rows="3"
                                                      placeholder="Observações importantes sobre o aluno...">{{ old('observations') }}</textarea>
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
                                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Aluno
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
// Máscaras para campos
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

    // Máscara para CPF do responsável
    const guardianCpfInput = document.getElementById('guardian_cpf');
    if (guardianCpfInput) {
        guardianCpfInput.addEventListener('input', function(e) {
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

    // Máscara para telefone do responsável
    const guardianPhoneInput = document.getElementById('guardian_phone');
    if (guardianPhoneInput) {
        guardianPhoneInput.addEventListener('input', function(e) {
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

    // Filtrar turmas baseado na escola selecionada
    const schoolSelect = document.getElementById('school_id');
    const classSelect = document.getElementById('class_id');

    if (schoolSelect && classSelect) {
        schoolSelect.addEventListener('change', function() {
            const schoolId = this.value;

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
                            classSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao buscar turmas:', error);
                    });
            }
        });
    }
});
</script>
@endpush
@endsection
