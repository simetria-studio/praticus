@extends('layouts.admin')

@section('title', 'Nova Escola')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('schools.index') }}">Escolas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Nova Escola</span>
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
                            Cadastrar Nova Escola
                        </h4>
                        <a href="{{ route('schools.index') }}" class="btn btn-light btn-sm">
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

                    <form method="POST" action="{{ route('schools.store') }}">
                        @csrf

                        <!-- Informações Básicas -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informações Básicas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nome da Escola <span class="text-danger">*</span></label>
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
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Código da Escola</label>
                                            <input type="text"
                                                   class="form-control @error('code') is-invalid @enderror"
                                                   id="code"
                                                   name="code"
                                                   value="{{ old('code') }}"
                                                   placeholder="Ex: ESC001">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cnpj" class="form-label">CNPJ</label>
                                            <input type="text"
                                                   class="form-control @error('cnpj') is-invalid @enderror"
                                                   id="cnpj"
                                                   name="cnpj"
                                                   value="{{ old('cnpj') }}"
                                                   placeholder="00.000.000/0000-00">
                                            @error('cnpj')
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
                                                   value="{{ old('phone') }}"
                                                   placeholder="(11) 99999-9999">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="escola@exemplo.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website</label>
                                            <input type="url"
                                                   class="form-control @error('website') is-invalid @enderror"
                                                   id="website"
                                                   name="website"
                                                   value="{{ old('website') }}"
                                                   placeholder="https://www.escola.com.br">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                                            <select class="form-select @error('type') is-invalid @enderror"
                                                    id="type"
                                                    name="type"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="publica" {{ old('type') == 'publica' ? 'selected' : '' }}>Pública</option>
                                                <option value="privada" {{ old('type') == 'privada' ? 'selected' : '' }}>Privada</option>
                                                <option value="federal" {{ old('type') == 'federal' ? 'selected' : '' }}>Federal</option>
                                                <option value="estadual" {{ old('type') == 'estadual' ? 'selected' : '' }}>Estadual</option>
                                                <option value="municipal" {{ old('type') == 'municipal' ? 'selected' : '' }}>Municipal</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="level" class="form-label">Nível de Ensino <span class="text-danger">*</span></label>
                                            <select class="form-select @error('level') is-invalid @enderror"
                                                    id="level"
                                                    name="level"
                                                    required>
                                                <option value="">Selecione...</option>
                                                <option value="infantil" {{ old('level') == 'infantil' ? 'selected' : '' }}>Educação Infantil</option>
                                                <option value="fundamental" {{ old('level') == 'fundamental' ? 'selected' : '' }}>Ensino Fundamental</option>
                                                <option value="medio" {{ old('level') == 'medio' ? 'selected' : '' }}>Ensino Médio</option>
                                                <option value="superior" {{ old('level') == 'superior' ? 'selected' : '' }}>Ensino Superior</option>
                                                <option value="tecnico" {{ old('level') == 'tecnico' ? 'selected' : '' }}>Ensino Técnico</option>
                                                <option value="todos" {{ old('level') == 'todos' ? 'selected' : '' }}>Todos os Níveis</option>
                                            </select>
                                            @error('level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="3"
                                              placeholder="Breve descrição da escola...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <div class="col-md-6">
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
                                </div>

                                <div class="row">
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
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="complement" class="form-label">Complemento</label>
                                            <input type="text"
                                                   class="form-control @error('complement') is-invalid @enderror"
                                                   id="complement"
                                                   name="complement"
                                                   value="{{ old('complement') }}"
                                                   placeholder="Sala, Bloco, etc.">
                                            @error('complement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude (Opcional)</label>
                                            <input type="number"
                                                   step="any"
                                                   class="form-control @error('latitude') is-invalid @enderror"
                                                   id="latitude"
                                                   name="latitude"
                                                   value="{{ old('latitude') }}"
                                                   placeholder="-23.5505">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude (Opcional)</label>
                                            <input type="number"
                                                   step="any"
                                                   class="form-control @error('longitude') is-invalid @enderror"
                                                   id="longitude"
                                                   name="longitude"
                                                   value="{{ old('longitude') }}"
                                                   placeholder="-46.6333">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-toggle-on me-2"></i>
                                    Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="active"
                                           name="active"
                                           value="1"
                                           {{ old('active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">
                                        Escola Ativa
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('schools.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Escola
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

    // Máscara para CNPJ
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 14) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
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
