@extends('layouts.admin')

@section('title', 'Coordenadores')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Coordenadores</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Coordenadores</h2>
        <a href="{{ route('coordinators.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Coordenador
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('coordinators.index') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nome, matrícula, email ou especialidade" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="school_id" class="form-select">
                            <option value="">Todas as escolas</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fas fa-search me-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Especialidade</th>
                            <th>Escola</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coordinators as $coordinator)
                        <tr>
                            <td>
                                <img src="{{ $coordinator->photo_url }}"
                                     alt="Foto do coordenador"
                                     class="rounded-circle"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-medium">{{ $coordinator->name }}</div>
                                <small class="text-muted">{{ $coordinator->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $coordinator->registration }}</span>
                            </td>
                            <td>
                                @if($coordinator->specialty)
                                    <span class="badge bg-info">{{ $coordinator->specialty }}</span>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>{{ $coordinator->school?->name }}</td>
                            <td>
                                <span class="badge {{ $coordinator->status_badge_class }}">{{ $coordinator->status_label }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('coordinators.show', $coordinator) }}" class="btn btn-sm btn-outline-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('coordinators.edit', $coordinator) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('coordinators.toggle-status', $coordinator) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-{{ $coordinator->status === 'ativo' ? 'secondary' : 'success' }}"
                                                title="{{ $coordinator->status === 'ativo' ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas fa-{{ $coordinator->status === 'ativo' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST"
                                          action="{{ route('coordinators.destroy', $coordinator) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja remover este coordenador?')">
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-user-tie fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">Nenhum coordenador encontrado.</p>
                                @if(request()->hasAny(['search', 'school_id', 'status']))
                                    <p class="text-muted">Tente ajustar os filtros ou</p>
                                @endif
                                <a href="{{ route('coordinators.create') }}" class="text-decoration-none">cadastre o primeiro coordenador</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $coordinators->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
