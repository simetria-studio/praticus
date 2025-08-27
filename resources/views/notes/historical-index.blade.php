@extends('layouts.admin')

@section('title', 'Históricos Escolares')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Históricos Escolares</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-scroll me-2"></i>
                            Históricos Escolares
                        </h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-users me-1"></i>
                                {{ $totalStudentsWithHistory }} estudantes
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-school me-1"></i>
                                {{ $totalSchoolsWithHistory }} escolas
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('historical-reports.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Buscar Estudante</label>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nome ou matrícula...">
                                </div>
                                <div class="col-md-3">
                                    <label for="school_id" class="form-label">Escola</label>
                                    <select class="form-select" id="school_id" name="school_id">
                                        <option value="">Todas as escolas</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                        <option value="transferido" {{ request('status') == 'transferido' ? 'selected' : '' }}>Transferido</option>
                                        <option value="formado" {{ request('status') == 'formado' ? 'selected' : '' }}>Formado</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="school_year" class="form-label">Ano Letivo</label>
                                    <select class="form-select" id="school_year" name="school_year">
                                        <option value="">Todos os anos</option>
                                        @foreach($schoolYears as $year)
                                            <option value="{{ $year }}" {{ request('school_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary mt-4">
                                        <i class="fas fa-search me-1"></i>
                                        Filtrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Grid de Estudantes -->
                    @if($students->count() > 0)
                        <div class="row">
                            @foreach($students as $student)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 border-left-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title mb-1">{{ $student->name }}</h5>
                                                    <small class="text-muted">Matrícula: {{ $student->enrollment }}</small>
                                                </div>
                                                <span class="badge {{ $student->status_badge_class }}">
                                                    {{ $student->status_label }}
                                                </span>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="fas fa-school me-2"></i>
                                                    <small>{{ $student->school->name }}</small>
                                                </div>
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    <small>{{ $student->grade_with_class }}</small>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <small>
                                                        Anos com notas:
                                                        @php
                                                            $years = $student->notes->pluck('school_year')->unique()->sort()->values();
                                                        @endphp
                                                        {{ $years->implode(', ') }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-1">
                                                <a href="{{ route('notes.historical-report', $student) }}"
                                                   class="btn btn-primary btn-sm flex-fill"
                                                   title="Ver Histórico">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Ver Histórico
                                                </a>
                                                <a href="{{ route('notes.historical-report.pdf', $student) }}"
                                                   class="btn btn-outline-danger btn-sm"
                                                   title="Baixar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('students.show', $student) }}"
                                                   class="btn btn-outline-info btn-sm"
                                                   title="Ver Perfil">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        @if($students->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $students->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-scroll fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">Nenhum histórico encontrado</h4>
                            <p class="text-muted mb-4">
                                @if(request()->hasAny(['search', 'school_id', 'status', 'school_year']))
                                    Não há estudantes com históricos que correspondam aos filtros aplicados.
                                @else
                                    Ainda não há estudantes com notas registradas para gerar históricos escolares.
                                @endif
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                @if(request()->hasAny(['search', 'school_id', 'status', 'school_year']))
                                    <a href="{{ route('historical-reports.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-filter me-1"></i>
                                        Limpar Filtros
                                    </a>
                                @endif
                                <a href="{{ route('notes.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Cadastrar Notas
                                </a>
                                <a href="{{ route('students.index') }}" class="btn btn-outline-info">
                                    <i class="fas fa-users me-1"></i>
                                    Ver Estudantes
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
