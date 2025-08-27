@extends('layouts.admin')

@section('title', 'Verificar Certificado')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Certificados</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Verificar</span>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Verificar Certificado
                    </h4>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                        <p class="text-muted">
                            Digite o código de verificação do certificado para validar sua autenticidade.
                        </p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('certificates.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="verification_code" class="form-label">Código de Verificação</label>
                            <input type="text"
                                   class="form-control form-control-lg text-center"
                                   id="verification_code"
                                   name="verification_code"
                                   value="{{ old('verification_code', request('verification_code')) }}"
                                   placeholder="Digite o código de verificação"
                                   required>
                            <div class="form-text">
                                O código de verificação está localizado no certificado.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>
                                Verificar Certificado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
