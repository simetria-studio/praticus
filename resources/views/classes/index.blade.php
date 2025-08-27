@extends('layouts.admin')

@section('title', 'Turmas')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Turmas</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Turmas</h2>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nome/código/serie" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="year" class="form-control" placeholder="Ano" value="{{ request('year') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Status</option>
                            <option value="ativa" {{ request('status')==='ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="inativa" {{ request('status')==='inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
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
                            <th>Nome</th>
                            <th>Código</th>
                            <th>Série</th>
                            <th>Período</th>
                            <th>Ano</th>
                            <th>Escola</th>
                            <th>Coordenador</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td><span class="badge bg-secondary">{{ $class->code }}</span></td>
                            <td>{{ $class->grade }}</td>
                            <td>{{ $class->period_label }}</td>
                            <td>{{ $class->year }}</td>
                            <td>{{ $class->school?->name }}</td>
                            <td>
                                @if($class->coordinator)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $class->coordinator->photo_url }}"
                                             alt="Foto do coordenador"
                                             class="rounded-circle me-2"
                                             style="width: 24px; height: 24px; object-fit: cover;">
                                        <div>
                                            <div class="fw-medium">{{ $class->coordinator->name }}</div>
                                            <small class="text-muted">{{ $class->coordinator->specialty }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Sem coordenador</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $class->status === 'ativa' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($class->status) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta turma?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Nenhuma turma encontrada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $classes->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
