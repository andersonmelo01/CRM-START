<!-- resources/views/usuarios/index.blade.php -->
@extends('layouts.app')

@section('titulo', 'Usuários')

@section('conteudo')
<div class="container">
    <h3 class="mb-4">Lista de Usuários</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">Novo Usuário</a>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->perfil) }}</td>
                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                <td>
                    <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('usuarios.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este usuário?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Deletar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Nenhum usuário encontrado</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $usuarios->links() }} <!-- Paginação -->
</div>
@endsection