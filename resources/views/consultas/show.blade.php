@extends('layouts.app')

@section('titulo', 'Consultas')

@section('conteudo')

<h3>Consulta</h3>

<p><strong>Paciente:</strong> {{ $consulta->paciente->nome }}</p>
<p><strong>Médico:</strong> {{ $consulta->medico->nome }}</p>
<p><strong>Data:</strong> {{ $consulta->data }} {{ $consulta->hora }}</p>

@if($consulta->prontuario)
<a href="{{ route('prontuarios.show', $consulta) }}"
    class="btn btn-info">
    Ver Prontuário
</a>
@else
<a href="{{ route('prontuarios.create', $consulta) }}"
    class="btn btn-primary">
    Iniciar Consulta
</a>
@endif

@endsection