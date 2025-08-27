@extends('layouts.app', ['hideNavbar' => true])

@section('content')
<div class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="text-center text-lg-start">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="mb-4" style="height: 80px;">
                    <h1 class="display-4 fw-bold text-primary-custom mb-4">
                        Sistema Escolar
                    </h1>
                    <p class="lead text-muted mb-4">
                        Gerencie históricos escolares e certificados de forma prática, eficiente e segura.
                        Uma solução completa para instituições de ensino.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Acessar Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Fazer Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="bg-primary-custom text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-graduation-cap fa-lg"></i>
                                    </div>
                                    <h5 class="card-title">Histórico Escolar</h5>
                                    <p class="card-text text-muted small">Gere históricos completos e personalizados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="bg-secondary-custom text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-certificate fa-lg"></i>
                                    </div>
                                    <h5 class="card-title">Certificados</h5>
                                    <p class="card-text text-muted small">Emita certificados profissionais</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #28a745;">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <h5 class="card-title">Gestão de Alunos</h5>
                                    <p class="card-text text-muted small">Controle completo de dados acadêmicos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #ffc107; color: #212529 !important;">
                                        <i class="fas fa-chart-bar fa-lg"></i>
                                    </div>
                                    <h5 class="card-title">Relatórios</h5>
                                    <p class="card-text text-muted small">Análises e estatísticas detalhadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection
