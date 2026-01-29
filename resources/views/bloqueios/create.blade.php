@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')

<h4>🛑 Bloquear Horário</h4>

<form method="POST" action="{{ route('bloqueios.store') }}">
    @csrf

    <div class="row g-3">

        <div class="col-md-4">
            <label>Data</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label>Hora Início</label>
            <input type="time" name="hora_inicio" class="form-control">
            <small class="text-muted">Deixe vazio para bloquear o dia inteiro</small>
        </div>

        <div class="col-md-4">
            <label>Hora Fim</label>
            <input type="time" name="hora_fim" class="form-control">
        </div>

        <div class="col-md-6">
            <label>Médico (opcional)</label>
            <select name="medico_id" class="form-control">
                <option value="">Todos</option>
                @foreach($medicos as $m)
                <option value="{{ $m->id }}">{{ $m->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>Motivo</label>
            <input type="text" name="motivo" class="form-control">
        </div>

    </div>

    <button class="btn btn-danger mt-3">Bloquear</button>

</form>

@endsection