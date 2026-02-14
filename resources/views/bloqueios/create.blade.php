@extends('layouts.app')

@section('titulo', 'Bloquear Horário')

@section('conteudo')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-slash-circle me-2 text-danger"></i> Bloquear Horário
        </h4>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    {{-- Card do Formulário --}}
    <div class="card shadow-sm rounded-4 p-4">
        <form method="POST" action="{{ route('bloqueios.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Data <span class="text-danger">*</span></label>
                    <input type="date" name="data" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hora Início</label>
                    <input type="time" name="hora_inicio" class="form-control">
                    <small class="text-muted d-block">Deixe vazio para bloquear o dia inteiro</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hora Fim</label>
                    <input type="time" name="hora_fim" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Médico (opcional)</label>
                    <select name="medico_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($medicos as $m)
                        <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Motivo</label>
                    <input type="text" name="motivo" class="form-control">
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-danger">
                    <i class="bi bi-slash-circle me-1"></i> Bloquear
                </button>
            </div>

        </form>
    </div>

</div>
@endsection