@extends('layouts.app')

@section('titulo', 'Novo Protuario')

@section('conteudo')

<h3>Prontuário</h3>

<p><strong>Paciente:</strong> {{ $consulta->paciente->nome }}</p>
<p><strong>Médico:</strong> {{ $consulta->medico->nome }}</p>

<hr>

<p><strong>Queixa:</strong> {{ $consulta->prontuario->queixa_principal }}</p>
<p><strong>Diagnóstico:</strong> {{ $consulta->prontuario->diagnostico }}</p>
<p><strong>Conduta:</strong> {{ $consulta->prontuario->conduta }}</p>


@endsection