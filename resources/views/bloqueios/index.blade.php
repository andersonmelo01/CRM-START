@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')

<h4>🚫 Horários Bloqueados</h4>

<table class="table table-striped">
    <tr>
        <th>Data</th>
        <th>Horário</th>
        <th>Médico</th>
        <th>Motivo</th>
    </tr>

    @foreach($bloqueios as $b)
    <tr>
        <td>{{ $b->data }}</td>
        <td>
            {{ $b->hora_inicio ? $b->hora_inicio.' às '.$b->hora_fim : 'Dia inteiro' }}
        </td>
        <td>{{ $b->medico->nome ?? 'Todos' }}</td>
        <td>{{ $b->motivo }}</td>
    </tr>
    @endforeach
</table>

@endsection