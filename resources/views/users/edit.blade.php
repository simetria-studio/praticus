@extends('layouts.admin')

@section('title', 'Editar Usuário')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Editar Usuário</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Usuário: {{ $user->name }}
                        </h4>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
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

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
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
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nova Senha</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           minlength="8"
                                           placeholder="Deixe em branco para manter a atual">
                                    <small class="form-text text-muted">Mínimo de 8 caracteres</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           minlength="8"
                                           placeholder="Confirme a nova senha">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Função <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required>
                                        <option value="">Selecione...</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Gerente</option>
                                        <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operador</option>
                                        <option value="coordinator" {{ old('role', $user->role) == 'coordinator' ? 'selected' : '' }}>Coordenador</option>
                                    </select>
                                    @error('role')
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
                                        <option value="ativo" {{ old('status', $user->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ old('status', $user->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3" id="coordinator_select" style="display: none;">
                                    <label for="coordinator_id" class="form-label">Coordenador <span class="text-danger">*</span></label>
                                    <select class="form-select @error('coordinator_id') is-invalid @enderror"
                                            id="coordinator_id"
                                            name="coordinator_id">
                                        <option value="">Selecione um coordenador...</option>
                                        @foreach($coordinators as $coordinator)
                                            <option value="{{ $coordinator->id }}"
                                                    {{ old('coordinator_id', $user->coordinator_id) == $coordinator->id ? 'selected' : '' }}>
                                                {{ $coordinator->name }} - {{ $coordinator->school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('coordinator_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Coordenador -->
                        @if($user->isCoordinator())
                        <div class="card mb-4" id="coordinator_info">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Informações do Coordenador
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nome:</strong> {{ $user->coordinator->name }}</p>
                                        <p><strong>Escola:</strong> {{ $user->coordinator->school->name }}</p>
                                        <p><strong>Especialidade:</strong> {{ $user->coordinator->specialty ?: 'Não informado' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Matrícula:</strong> {{ $user->coordinator->registration }}</p>
                                        <p><strong>E-mail:</strong> {{ $user->coordinator->email }}</p>
                                        <p><strong>Status:</strong>
                                            <span class="badge {{ $user->coordinator->status_badge_class }}">
                                                {{ $user->coordinator->status_label }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Atualizar Usuário
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
    const roleSelect = document.getElementById('role');
    const coordinatorSelect = document.getElementById('coordinator_select');
    const coordinatorIdSelect = document.getElementById('coordinator_id');
    const coordinatorInfo = document.getElementById('coordinator_info');

    function toggleCoordinatorSelect() {
        if (roleSelect.value === 'coordinator') {
            coordinatorSelect.style.display = 'block';
            coordinatorIdSelect.required = true;
            if (coordinatorInfo) coordinatorInfo.style.display = 'block';
        } else {
            coordinatorSelect.style.display = 'none';
            coordinatorIdSelect.required = false;
            coordinatorIdSelect.value = '';
            if (coordinatorInfo) coordinatorInfo.style.display = 'none';
        }
    }

    roleSelect.addEventListener('change', toggleCoordinatorSelect);
    toggleCoordinatorSelect(); // Executar na carga inicial
});
</script>
@endpush
@endsection
