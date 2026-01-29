@extends('layouts.app')

@section('titulo', 'Protuario')

@section('conteudo')

<div class="container">
    <h3>Prontuário</h3>

    <p><strong>Paciente:</strong> {{ $consulta->paciente->nome }}</p>
    <p><strong>Médico:</strong> {{ $consulta->medico->nome }}</p>
    <p><strong>Data:</strong> {{ $consulta->data }} {{ $consulta->hora }}</p>

    <hr>

    <p><strong>Queixa Principal:</strong></p>
    <p>{{ $prontuario->queixa  ?? 'Não informado' }}</p>

    <p><strong>Diagnóstico:</strong></p>
    <p>{{ $prontuario->diagnostico ?? 'Não informado' }}</p>

    <p><strong>Conduta:</strong></p>
    <p>{{ $prontuario->conduta ?? 'Não informado' }}</p>

    <a href="{{ route('consultas.index') }}" class="btn btn-secondary">
        Voltar
    </a>
</div>

@endsection