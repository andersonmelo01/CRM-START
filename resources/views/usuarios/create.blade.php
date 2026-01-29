@extends('layouts.app')

@section('titulo', 'Cadastrar Usuário')

@section('conteudo')
<div class="container">
    <h3>Cadastrar Usuário</h3>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="perfil">Perfil</label>
            <select name="perfil" id="perfil" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="medico">Médico</option>
                <option value="secretaria">Recepção</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection