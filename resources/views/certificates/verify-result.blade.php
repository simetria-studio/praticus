@extends('layouts.admin')

@section('title', 'Resultado da Verificação')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Certificados</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Resultado</span>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Certificado Verificado
                    </h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-success text-center mb-4">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                        <h5>Certificado Autêntico!</h5>
                        <p class="mb-0">Este certificado é válido e foi emitido oficialmente.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-certificate me-2"></i>
                                        Dados do Certificado
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Número:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->certificate_number }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Tipo:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->certificate_type_label }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Nível:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->course_level_label }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Ano:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->completion_year }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Emissão:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->formatted_issue_date }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Status:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge {{ $certificate->status_badge_class }}">
                                                {{ $certificate->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Dados do Aluno
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Nome:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->student_name }}</div>
                                    </div>
                                    @if($certificate->student_cpf)
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>CPF:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->formatted_student_cpf }}</div>
                                        </div>
                                    @endif
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Nascimento:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->formatted_student_birth_date }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Naturalidade:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->student_birth_place }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Mãe:</strong></div>
                                        <div class="col-sm-8">{{ $certificate->mother_name }}</div>
                                    </div>
                                    @if($certificate->father_name)
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Pai:</strong></div>
                                            <div class="col-sm-8">{{ $certificate->father_name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-school me-2"></i>
                                        Dados da Escola
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-4"><strong>Nome:</strong></div>
                                                <div class="col-sm-8">{{ $certificate->school_name }}</div>
                                            </div>
                                            @if($certificate->school_cnpj)
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>CNPJ:</strong></div>
                                                    <div class="col-sm-8">{{ $certificate->formatted_school_cnpj }}</div>
                                                </div>
                                            @endif
                                            @if($certificate->school_inep)
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>INEP:</strong></div>
                                                    <div class="col-sm-8">{{ $certificate->school_inep }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-4"><strong>Cidade:</strong></div>
                                                <div class="col-sm-8">{{ $certificate->issue_city }} - {{ $certificate->issue_state }}</div>
                                            </div>
                                            @if($certificate->school_authorization)
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Autorização:</strong></div>
                                                    <div class="col-sm-8">{{ $certificate->school_authorization }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('certificates.verify') }}" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Verificar Outro Certificado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
