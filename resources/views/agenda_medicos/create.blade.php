@extends('layouts.app')

@section('titulo', 'Nova Agenda Médica')

@section('conteudo')
<div class="container py-4">
    <h4 class="mb-4">
        <i class="bi bi-calendar-plus text-primary"></i>
        Cadastrar Agenda do Médico
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('agenda-medicos.store') }}">
                @csrf

                <div class="row g-3">
                    {{-- MÉDICO --}}
                    <div class="col-md-6">
                        <label class="form-label">Médico</label>
                        <select name="medico_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($medicos as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DATA --}}
                    <div class="col-md-3">
                        <label class="form-label">Data</label>
                        <input type="date" name="data" class="form-control" required>
                    </div>

                    {{-- INTERVALO --}}
                    <div class="col-md-3">
                        <label class="form-label">Intervalo (min)</label>
                        <input type="number" name="intervalo" class="form-control" value="30" required>
                    </div>

                    {{-- HORA INÍCIO --}}
                    <div class="col-md-3">
                        <label class="form-label">Hora Início</label>
                        <input type="time" name="hora_inicio" class="form-control" required>
                    </div>

                    {{-- HORA FIM --}}
                    <div class="col-md-3">
                        <label class="form-label">Hora Fim</label>
                        <input type="time" name="hora_fim" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('agenda-medicos.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i>
                        Salvar Agenda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection