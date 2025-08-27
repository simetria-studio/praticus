@extends('layouts.admin')

@section('title', 'Editar Professor')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Professores</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Editar Professor</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Editar Professor</h2>
                <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar à Lista
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Dados do Professor
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teachers.update', $teacher) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Dados Pessoais -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Dados Pessoais
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome Completo *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $teacher->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $teacher->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telefone</label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $teacher->phone) }}"
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
                                           value="{{ old('cpf', $teacher->cpf) }}"
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
                                           value="{{ old('rg', $teacher->rg) }}">
                                    @error('rg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                                    <input type="date"
                                           class="form-control @error('birth_date') is-invalid @enderror"
                                           id="birth_date"
                                           name="birth_date"
                                           value="{{ old('birth_date', $teacher->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gênero</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Selecione...</option>
                                        <option value="masculino" {{ old('gender', $teacher->gender) === 'masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="feminino" {{ old('gender', $teacher->gender) === 'feminino' ? 'selected' : '' }}>Feminino</option>
                                        <option value="outro" {{ old('gender', $teacher->gender) === 'outro' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Foto</label>
                                    @if($teacher->photo)
                                        <div class="mb-2">
                                            <img src="{{ $teacher->photo_url }}"
                                                 alt="Foto atual"
                                                 class="rounded"
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file"
                                           class="form-control @error('photo') is-invalid @enderror"
                                           id="photo"
                                           name="photo"
                                           accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Máximo: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Profissionais -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-briefcase me-2"></i>
                                    Dados Profissionais
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration" class="form-label">Matrícula Funcional *</label>
                                    <input type="text"
                                           class="form-control @error('registration') is-invalid @enderror"
                                           id="registration"
                                           name="registration"
                                           value="{{ old('registration', $teacher->registration) }}"
                                           required>
                                    @error('registration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialty" class="form-label">Especialidade *</label>
                                    <select class="form-select @error('specialty') is-invalid @enderror" id="specialty" name="specialty" required>
                                        <option value="">Selecione...</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty }}" {{ old('specialty', $teacher->specialty) === $specialty ? 'selected' : '' }}>
                                                {{ $specialty }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="degree" class="form-label">Formação Acadêmica</label>
                                    <input type="text"
                                           class="form-control @error('degree') is-invalid @enderror"
                                           id="degree"
                                           name="degree"
                                           value="{{ old('degree', $teacher->degree) }}"
                                           placeholder="Ex: Licenciatura em Matemática">
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
                                           value="{{ old('institution', $teacher->institution) }}">
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
                                           value="{{ old('graduation_year', $teacher->graduation_year) }}"
                                           min="1900"
                                           max="{{ date('Y') + 1 }}">
                                    @error('graduation_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Selecione...</option>
                                        <option value="ativo" {{ old('status', $teacher->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ old('status', $teacher->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                                        <option value="aposentado" {{ old('status', $teacher->status) === 'aposentado' ? 'selected' : '' }}>Aposentado</option>
                                        <option value="licenca" {{ old('status', $teacher->status) === 'licenca' ? 'selected' : '' }}>Licença</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hiring_date" class="form-label">Data de Contratação</label>
                                    <input type="date"
                                           class="form-control @error('hiring_date') is-invalid @enderror"
                                           id="hiring_date"
                                           name="hiring_date"
                                           value="{{ old('hiring_date', $teacher->hiring_date?->format('Y-m-d')) }}">
                                    @error('hiring_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">Tipo de Contrato</label>
                                    <select class="form-select @error('contract_type') is-invalid @enderror" id="contract_type" name="contract_type">
                                        <option value="">Selecione...</option>
                                        <option value="efetivo" {{ old('contract_type', $teacher->contract_type) === 'efetivo' ? 'selected' : '' }}>Efetivo</option>
                                        <option value="temporario" {{ old('contract_type', $teacher->contract_type) === 'temporario' ? 'selected' : '' }}>Temporário</option>
                                        <option value="contratado" {{ old('contract_type', $teacher->contract_type) === 'contratado' ? 'selected' : '' }}>Contratado</option>
                                        <option value="estagiario" {{ old('contract_type', $teacher->contract_type) === 'estagiario' ? 'selected' : '' }}>Estagiário</option>
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salário</label>
                                    <input type="number"
                                           class="form-control @error('salary') is-invalid @enderror"
                                           id="salary"
                                           name="salary"
                                           value="{{ old('salary', $teacher->salary) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00">
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="workload" class="form-label">Carga Horária</label>
                                    <input type="text"
                                           class="form-control @error('workload') is-invalid @enderror"
                                           id="workload"
                                           name="workload"
                                           value="{{ old('workload', $teacher->workload) }}"
                                           placeholder="Ex: 40h semanais">
                                    @error('workload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Endereço
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="street" class="form-label">Rua</label>
                                    <input type="text"
                                           class="form-control @error('street') is-invalid @enderror"
                                           id="street"
                                           name="street"
                                           value="{{ old('street', $teacher->street) }}">
                                    @error('street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="number" class="form-label">Número</label>
                                    <input type="text"
                                           class="form-control @error('number') is-invalid @enderror"
                                           id="number"
                                           name="number"
                                           value="{{ old('number', $teacher->number) }}">
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="complement" class="form-label">Complemento</label>
                                    <input type="text"
                                           class="form-control @error('complement') is-invalid @enderror"
                                           id="complement"
                                           name="complement"
                                           value="{{ old('complement', $teacher->complement) }}">
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
                                           value="{{ old('neighborhood', $teacher->neighborhood) }}">
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
                                           value="{{ old('city', $teacher->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="state" class="form-label">Estado</label>
                                    <input type="text"
                                           class="form-control @error('state') is-invalid @enderror"
                                           id="state"
                                           name="state"
                                           value="{{ old('state', $teacher->state) }}"
                                           maxlength="2"
                                           placeholder="SP">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">CEP</label>
                                    <input type="text"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           id="postal_code"
                                           name="postal_code"
                                           value="{{ old('postal_code', $teacher->postal_code) }}"
                                           placeholder="00000-000">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Disciplinas e Escolas -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-book me-2"></i>
                                    Disciplinas e Escolas
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subjects" class="form-label">Disciplinas que Leciona</label>
                                    <select class="form-select @error('subjects') is-invalid @enderror" id="subjects" name="subjects[]" multiple>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty }}" {{ in_array($specialty, old('subjects', $teacher->subjects ?? [])) ? 'selected' : '' }}>
                                                {{ $specialty }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subjects')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplas disciplinas</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="schools" class="form-label">Escolas onde Atua</label>
                                    <select class="form-select @error('schools') is-invalid @enderror" id="schools" name="schools[]" multiple>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ in_array($school->id, old('schools', $teacher->schools ?? [])) ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('schools')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplas escolas</small>
                                </div>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observações</label>
                                    <textarea class="form-control @error('observations') is-invalid @enderror"
                                              id="observations"
                                              name="observations"
                                              rows="3"
                                              placeholder="Informações adicionais sobre o professor...">{{ old('observations', $teacher->observations) }}</textarea>
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
                                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Atualizar Professor
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
</style>

<script>
// Máscaras para os campos
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }

    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length === 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length === 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
            e.target.value = value;
        });
    }

    // Máscara para CEP
    const cepInput = document.getElementById('postal_code');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }
});
</script>
@endsection
