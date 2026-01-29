@extends('layouts.app')

@section('titulo', 'Bloqueio de Horários')

@section('conteudo')

<div class="container">
    <h3 class="mb-4">🚫 Bloqueio de Horários</h3>

    <form method="POST" action="{{ route('bloqueios.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-3">
                <label>Data</label>
                <input type="date" name="data" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Hora início</label>
                <input type="time" name="hora_inicio" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Hora fim</label>
                <input type="time" name="hora_fim" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Médico (opcional)</label>
                <select name="medico_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($medicos as $m)
                    <option value="{{ $m->id }}">{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3">
            <label>Motivo</label>
            <textarea name="motivo" class="form-control"></textarea>
        </div>

        <button class="btn btn-danger mt-3">Bloquear</button>
    </form>
</div>

@endsection