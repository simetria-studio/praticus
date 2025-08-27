@extends('layouts.admin')

@section('title', 'Dashboard - ' . $school->name)

@section('breadcrumb')
<span class="breadcrumb-item active">Dashboard</span>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho da Escola -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary-custom text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">{{ $school->name }}</h2>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $school->city }}/{{ $school->state }} - {{ $school->address }}
                            </p>
                        </div>
                        <div class="text-end">
                            <h4 class="mb-1">Coordenador</h4>
                            <p class="mb-0">{{ auth()->user()->coordinator->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Alunos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['students_count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Turmas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['classes_count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Professores
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['teachers_count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Certificados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['certificates_count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="row">
        <!-- Alunos Recentes -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Alunos Recentes</h6>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    @if($recentStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Turma</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $student->photo_url }}"
                                                     alt="Foto do aluno"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-medium">{{ $student->name }}</div>
                                                    <small class="text-muted">{{ $student->registration }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($student->schoolClass)
                                                <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                                            @else
                                                <span class="text-muted">Sem turma</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $student->status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $student->status === 'ativo' ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Nenhum aluno cadastrado ainda.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Turmas Recentes -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Turmas Recentes</h6>
                    <a href="{{ route('classes.index') }}" class="btn btn-sm btn-success">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($recentClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Série</th>
                                        <th>Alunos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClasses as $class)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $class->name }}</div>
                                            <small class="text-muted">{{ $class->code }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $class->grade }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $class->students_count ?? 0 }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Nenhuma turma cadastrada ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Certificados Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Certificados Recentes</h6>
                    <a href="{{ route('certificates.index') }}" class="btn btn-sm btn-warning">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    @if($recentCertificates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Aluno</th>
                                        <th>Curso</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCertificates as $certificate)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $certificate->student->photo_url }}"
                                                     alt="Foto do aluno"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-medium">{{ $certificate->student->name }}</div>
                                                    <small class="text-muted">{{ $certificate->student->registration }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $certificate->course_name }}</div>
                                            <small class="text-muted">{{ $certificate->course_duration }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $certificate->completion_date?->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $certificate->status === 'emitido' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($certificate->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Nenhum certificado emitido ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('students.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <br>Novo Aluno
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('classes.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>Nova Turma
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('certificates.create') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-certificate fa-2x mb-2"></i>
                                <br>Novo Certificado
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('notes.create') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-sticky-note fa-2x mb-2"></i>
                                <br>Nova Nota
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #058D4F !important;
}

.border-left-success {
    border-left: 0.25rem solid #28a745 !important;
}

.border-left-info {
    border-left: 0.25rem solid #17a2b8 !important;
}

.border-left-warning {
    border-left: 0.25rem solid #ffc107 !important;
}

.text-primary {
    color: #058D4F !important;
}

.text-success {
    color: #28a745 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.btn-outline-primary {
    color: #058D4F;
    border-color: #058D4F;
}

.btn-outline-primary:hover {
    color: #fff;
    background-color: #058D4F;
    border-color: #058D4F;
}

.btn-outline-success {
    color: #28a745;
    border-color: #28a745;
}

.btn-outline-success:hover {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}

.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-warning:hover {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-info {
    color: #17a2b8;
    border-color: #17a2b8;
}

.btn-outline-info:hover {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
</style>
@endsection
