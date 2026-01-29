@extends('layouts.app')

@section('titulo', 'Configuração')

@section('conteudo')

<h1>Editar</h1>

<a href="{{ route('medicos.bloqueados') }}" class="btn btn-success">
    Bloqueios
</a>
<a href="{{ route('usuarios.index') }}" class="btn btn-success">Usuario</a>
<a href="{{ route('emitente.edit') }}" class="btn btn-success">Emitente</a>
<a href="{{ route('relatorio.financeiro') }}" class="btn btn-success">Relatórios</a>
@endsection