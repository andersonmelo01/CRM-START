@extends('layouts.app')

@section('titulo', 'Pacientes')

@section('conteudo')
<a href="{{ route('pacientes.create') }}" class="btn btn-success mb-2">Novo Paciente</a>

<table class="table table-bordered">
    <tr>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>

    @foreach($pacientes as $p)
    <tr>
        <td>{{ $p->nome }}</td>
        <td>{{ $p->telefone }}</td>
        <td>
            <a href="{{ route('pacientes.edit',$p) }}" class="btn btn-warning">Editar</a>
            <form method="POST" action="{{ route('pacientes.destroy',$p) }}" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Excluir</button>
            </form>
        </td>
        <td>
            <a href="{{ route('pacientes.historico', $p->id) }}"
                class="btn btn-sm btn-outline-dark">
                Histórico
            </a>
        </td>
    </tr>
    @endforeach
</table>

@endsection