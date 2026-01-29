@extends('layouts.app')

@section('titulo', 'Cadastrar Médico')

@section('conteudo')

<h4>Médicos bloqueados agora</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Médico</th>
            <th>Horário</th>
            <th>Motivo</th>
        </tr>
    </thead>
    <tbody>
       
        @forelse($medicos as $medico)
        @foreach($medico->bloqueios as $b)
        <tr>
            <td>{{ $medico->nome }}</td>
            <td>
                {{ $b->data }}
                {{ $b->hora_inicio }} - {{ $b->hora_fim }}
            </td>
            <td>{{ $b->motivo }}</td>
        </tr>
        @endforeach
        @empty
        <tr>
            <td colspan="3">Nenhum médico bloqueado no momento</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection