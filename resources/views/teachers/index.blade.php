@extends('layouts.admin')

@section('title', 'Professores')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Professores</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Professores</h2>
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Professor
                </a>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total de Professores
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $stats['total'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Professores Ativos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $stats['active'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-secondary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Professores Inativos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $stats['inactive'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-times fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Aposentados
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $stats['retired'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-clock fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('teachers.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nome, email, matrícula ou especialidade">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos os status</option>
                                        <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                                        <option value="aposentado" {{ request('status') === 'aposentado' ? 'selected' : '' }}>Aposentado</option>
                                        <option value="licenca" {{ request('status') === 'licenca' ? 'selected' : '' }}>Licença</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="specialty" class="form-label">Especialidade</label>
                                    <select class="form-select" id="specialty" name="specialty">
                                        <option value="">Todas as especialidades</option>
                                        <option value="Matemática" {{ request('specialty') === 'Matemática' ? 'selected' : '' }}>Matemática</option>
                                        <option value="Português" {{ request('specialty') === 'Português' ? 'selected' : '' }}>Português</option>
                                        <option value="História" {{ request('specialty') === 'História' ? 'selected' : '' }}>História</option>
                                        <option value="Geografia" {{ request('specialty') === 'Geografia' ? 'selected' : '' }}>Geografia</option>
                                        <option value="Ciências" {{ request('specialty') === 'Ciências' ? 'selected' : '' }}>Ciências</option>
                                        <option value="Física" {{ request('specialty') === 'Física' ? 'selected' : '' }}>Física</option>
                                        <option value="Química" {{ request('specialty') === 'Química' ? 'selected' : '' }}>Química</option>
                                        <option value="Biologia" {{ request('specialty') === 'Biologia' ? 'selected' : '' }}>Biologia</option>
                                        <option value="Educação Física" {{ request('specialty') === 'Educação Física' ? 'selected' : '' }}>Educação Física</option>
                                        <option value="Arte" {{ request('specialty') === 'Arte' ? 'selected' : '' }}>Arte</option>
                                        <option value="Inglês" {{ request('specialty') === 'Inglês' ? 'selected' : '' }}>Inglês</option>
                                        <option value="Espanhol" {{ request('specialty') === 'Espanhol' ? 'selected' : '' }}>Espanhol</option>
                                        <option value="Filosofia" {{ request('specialty') === 'Filosofia' ? 'selected' : '' }}>Filosofia</option>
                                        <option value="Sociologia" {{ request('specialty') === 'Sociologia' ? 'selected' : '' }}>Sociologia</option>
                                        <option value="Literatura" {{ request('specialty') === 'Literatura' ? 'selected' : '' }}>Literatura</option>
                                        <option value="Redação" {{ request('specialty') === 'Redação' ? 'selected' : '' }}>Redação</option>
                                        <option value="Informática" {{ request('specialty') === 'Informática' ? 'selected' : '' }}>Informática</option>
                                        <option value="Música" {{ request('specialty') === 'Música' ? 'selected' : '' }}>Música</option>
                                        <option value="Teatro" {{ request('specialty') === 'Teatro' ? 'selected' : '' }}>Teatro</option>
                                        <option value="Dança" {{ request('specialty') === 'Dança' ? 'selected' : '' }}>Dança</option>
                                        <option value="Outras" {{ request('specialty') === 'Outras' ? 'selected' : '' }}>Outras</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>
                                            Filtrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(request()->hasAny(['search', 'status', 'specialty']))
                            <div class="text-center">
                                <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>
                                    Limpar Filtros
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tabela de Professores -->
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Professores
                        </h5>
                        <div class="badge bg-light text-dark">
                            Total: {{ $teachers->total() }}
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($teachers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">Foto</th>
                                        <th style="width: 20%">Nome</th>
                                        <th style="width: 15%">Especialidade</th>
                                        <th style="width: 15%">Contato</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 15%">Informações</th>
                                        <th style="width: 20%">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $teacher)
                                    <tr>
                                        <td>
                                            <img src="{{ $teacher->photo_url }}"
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $teacher->name }}</div>
                                                <small class="text-muted">{{ $teacher->registration }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $teacher->specialty }}</span>
                                            @if($teacher->degree)
                                                <br><small class="text-muted">{{ $teacher->degree }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $teacher->email }}</div>
                                                @if($teacher->phone)
                                                    <small class="text-muted">{{ $teacher->formatted_phone }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $teacher->status_badge_class }}">
                                                {{ $teacher->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                @if($teacher->birth_date)
                                                    <small class="text-muted">{{ $teacher->age }} anos</small><br>
                                                @endif
                                                @if($teacher->hiring_date)
                                                    <small class="text-muted">{{ $teacher->years_of_service }} anos de serviço</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teachers.show', $teacher) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teachers.edit', $teacher) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('teachers.toggle-status', $teacher) }}"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-{{ $teacher->status === 'ativo' ? 'secondary' : 'success' }}"
                                                            title="{{ $teacher->status === 'ativo' ? 'Desativar' : 'Ativar' }}">
                                                        <i class="fas fa-{{ $teacher->status === 'ativo' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                      action="{{ route('teachers.destroy', $teacher) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Tem certeza que deseja remover este professor?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Remover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="card-footer">
                            {{ $teachers->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum professor encontrado</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'status', 'specialty']))
                                    Tente ajustar os filtros ou
                                @endif
                                <a href="{{ route('teachers.create') }}" class="text-decoration-none">cadastre o primeiro professor</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-xs {
    font-size: 0.7rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-primary-custom {
    background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%) !important;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
