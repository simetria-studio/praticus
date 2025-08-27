@extends('layouts.admin')

@section('title', 'Turma: ' . $class->name)

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">{{ $class->name }}</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Turma: {{ $class->name }}</h2>
        <div>
            <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i>Editar</a>
            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Voltar</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-primary-custom text-white">Informações</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Código:</strong> {{ $class->code }}</p>
                    <p class="mb-1"><strong>Série:</strong> {{ $class->grade }}</p>
                    <p class="mb-1"><strong>Período:</strong> {{ $class->period_label }}</p>
                    <p class="mb-1"><strong>Ano:</strong> {{ $class->year }}</p>
                    <p class="mb-1"><strong>Escola:</strong> {{ $class->school?->name }}</p>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge {{ $class->status==='ativa' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($class->status) }}</span></p>
                    @if($class->capacity)
                    <p class="mb-1"><strong>Capacidade:</strong> {{ $class->capacity }}</p>
                    @endif
                    @if($class->coordinator)
                    <p class="mb-1">
                        <strong>Coordenador:</strong>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ $class->coordinator->photo_url }}"
                                 alt="Foto do coordenador"
                                 class="rounded-circle me-3"
                                 style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <div class="fw-bold">{{ $class->coordinator->name }}</div>
                                <div class="text-muted">{{ $class->coordinator->specialty }}</div>
                                <div class="small text-muted">Matrícula: {{ $class->coordinator->registration }}</div>
                                @if($class->coordinator->phone)
                                <div class="small text-muted">Tel: {{ $class->coordinator->formatted_phone }}</div>
                                @endif
                            </div>
                        </div>
                    </p>
                    @else
                    <p class="mb-1"><strong>Coordenador:</strong> <span class="text-muted">Não definido</span></p>
                    @endif
                    @if($class->start_date)
                    <p class="mb-1"><strong>Início:</strong> {{ $class->start_date->format('d/m/Y') }}</p>
                    @endif
                    @if($class->end_date)
                    <p class="mb-0"><strong>Término:</strong> {{ $class->end_date->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>

            @if($class->description)
            <div class="card mb-3">
                <div class="card-header">Descrição</div>
                <div class="card-body">
                    <p class="mb-0">{{ $class->description }}</p>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Alunos da Turma</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->enrollment }}</td>
                                    <td><span class="badge {{ $student->status_badge_class }}">{{ $student->status_label }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum aluno nesta turma.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
