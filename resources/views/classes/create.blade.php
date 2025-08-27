@extends('layouts.admin')

@section('title', 'Nova Turma')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('classes.index') }}">Turmas</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Nova</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Nova Turma</h2>
        <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary-custom text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Dados da Turma</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('classes.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nome *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Código *</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Série *</label>
                        <input type="text" class="form-control" name="grade" value="{{ old('grade') }}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Período *</label>
                        <select class="form-select" name="period" required>
                            <option value="manha" {{ old('period')==='manha' ? 'selected' : '' }}>Manhã</option>
                            <option value="tarde" {{ old('period')==='tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noite" {{ old('period')==='noite' ? 'selected' : '' }}>Noite</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Ano *</label>
                        <input type="number" class="form-control" name="year" value="{{ old('year', date('Y')) }}" min="2020" max="2035" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Capacidade</label>
                        <input type="number" class="form-control" name="capacity" value="{{ old('capacity') }}" min="1" max="100">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Escola *</label>
                        <select class="form-select" name="school_id" id="school_id" required>
                            <option value="">Selecione...</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id')==$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Coordenador</label>
                        <select class="form-select" name="coordinator_id" id="coordinator_id">
                            <option value="">Sem coordenador</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('coordinator_id')==$teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Selecione primeiro uma escola para ver os professores disponíveis</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="ativa" {{ old('status','ativa')==='ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="inativa" {{ old('status')==='inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Início</label>
                        <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Término</label>
                        <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" rows="3" name="description">{{ old('description') }}</textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrar professores baseado na escola selecionada
    const schoolSelect = document.getElementById('school_id');
    const coordinatorSelect = document.getElementById('coordinator_id');

    if (schoolSelect && coordinatorSelect) {
        schoolSelect.addEventListener('change', function() {
            const schoolId = this.value;

            // Limpar coordenador selecionado
            coordinatorSelect.innerHTML = '<option value="">Sem coordenador</option>';

            if (schoolId) {
                // Buscar professores da escola selecionada
                fetch(`/api/schools/${schoolId}/teachers`)
                    .then(response => response.json())
                    .then(teachers => {
                        teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.name} - ${teacher.specialty || 'Sem especialidade'} (${teacher.registration})`;
                            coordinatorSelect.appendChild(option);
                        });

                        if (teachers.length === 0) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Nenhum professor disponível nesta escola';
                            option.disabled = true;
                            coordinatorSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar professores:', error);
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Erro ao carregar professores';
                        option.disabled = true;
                        coordinatorSelect.appendChild(option);
                    });
            }
        });
    }
});
</script>
@endpush
@endsection
