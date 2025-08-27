@extends('layouts.admin')

@section('title', 'Coordenador - ' . $coordinator->name)

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('coordinators.index') }}">Coordenadores</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Visualizar</span>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $linkedUser = $coordinator->users()->first();
    @endphp

    @if(session('generated_password'))
        <div class="alert alert-info">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-user-check fa-lg me-2 text-primary"></i>
                <strong>Acesso do coordenador liberado!</strong>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="small text-muted">E-mail de login</div>
                    <code>{{ $coordinator->email }}</code>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">Senha provisória</div>
                    <code>{{ session('generated_password') }}</code>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">Observação</div>
                    <span>Peça para alterar a senha no primeiro acesso.</span>
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-dark mb-0">
            <i class="fas fa-user-tie me-2 text-primary"></i>
            {{ $coordinator->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('coordinators.edit', $coordinator) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            @if(!$linkedUser)
                <form method="POST" action="{{ route('coordinators.enable-access', $coordinator) }}" onsubmit="return confirm('Liberar acesso para este coordenador? Será criada uma conta e gerada senha provisória.');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-unlock me-1"></i> Liberar acesso
                    </button>
                </form>
            @else
                <span class="badge bg-success align-self-center p-2">
                    <i class="fas fa-user-check me-1"></i> Acesso já liberado
                </span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <img src="{{ $coordinator->photo_url }}" alt="Foto" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="mb-2">
                        <span class="badge {{ $coordinator->status_badge_class }}">{{ $coordinator->status_label }}</span>
                        @if($coordinator->specialty)
                            <span class="badge bg-info ms-1">{{ $coordinator->specialty }}</span>
                        @endif
                    </div>
                    <div class="small text-muted">Matrícula</div>
                    <div class="fw-medium">{{ $coordinator->registration }}</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-key me-2"></i> Acesso ao sistema
                </div>
                <div class="card-body">
                    @if($linkedUser)
                        <div class="mb-2">
                            <div class="small text-muted">Usuário</div>
                            <div class="fw-medium">{{ $linkedUser->name }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="small text-muted">E-mail</div>
                            <div class="fw-medium">{{ $linkedUser->email }}</div>
                        </div>
                        <div>
                            <div class="small text-muted">Status</div>
                            <span class="badge {{ $linkedUser->status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($linkedUser->status) }}</span>
                        </div>
                    @else
                        <p class="text-muted mb-2">Nenhum usuário criado ainda.</p>
                        <form method="POST" action="{{ route('coordinators.enable-access', $coordinator) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-unlock me-1"></i> Liberar acesso agora
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-id-badge me-2"></i> Dados do coordenador
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">E-mail</div>
                            <div class="fw-medium">{{ $coordinator->email }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">Telefone</div>
                            <div class="fw-medium">{{ $coordinator->formatted_phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">Escola</div>
                            <div class="fw-medium">{{ $coordinator->school?->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">Formação</div>
                            <div class="fw-medium">{{ $coordinator->degree ?? '—' }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="small text-muted">Endereço</div>
                            <div class="fw-medium">{{ $coordinator->full_address ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-2"></i> Áreas de coordenação
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">Séries/Anos</div>
                            <div class="fw-medium">{{ $coordinator->coordinated_grades_list }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted">Disciplinas</div>
                            <div class="fw-medium">{{ $coordinator->coordinated_subjects_list }}</div>
                        </div>
                    </div>
                    <div class="small text-muted">Observações</div>
                    <div>{{ $coordinator->observations ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
