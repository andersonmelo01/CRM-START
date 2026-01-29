@extends('layouts.app')

@section('titulo', 'Medicos')

@section('conteudo')

<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
        <h5>Médicos</h5>
        <a href="{{ route('medicos.create') }}" class="btn btn-success btn-sm">
            + Novo Médico
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <input type="text"
                name="busca"
                class="form-control"
                placeholder="Buscar por nome, CRM ou especialidade"
                value="{{ request('busca') }}">
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CRM</th>
                    <th>Especialidade</th>
                    <th>Status</th>
                    <th width="160">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($medicos as $m)
                <tr>
                    <td>{{ $m->nome }}</td>
                    <td>{{ $m->crm }}</td>
                    <td>{{ $m->especialidade }}</td>
                    <td>
                        @if($m->estaBloqueadoEm(now()->format('Y-m-d'), now()->format('H:i')))
                        <span class="badge bg-danger">Bloqueado agora</span>
                        @else
                        <span class="badge bg-success">Disponível</span>
                        @endif

                    </td>
                    <td>
                        <a href="{{ route('medicos.edit', $m) }}"
                            class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('medicos.destroy', $m) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Excluir médico?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhum médico encontrado</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $medicos->links() }}

    </div>
</div>

@endsection