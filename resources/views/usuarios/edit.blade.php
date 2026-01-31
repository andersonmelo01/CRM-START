<!-- resources/views/usuarios/edit.blade.php -->
@extends('layouts.app')

@section('titulo', 'Editar Usuário')

@section('conteudo')
<div class="container">
    <h3>Editar Usuário</h3>

    <form action="{{ route('usuarios.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Perfil</label>
            <select name="perfil" class="form-control">
                <option value="admin" {{ old('perfil', $user->perfil) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="medico" {{ old('perfil', $user->perfil) == 'medico' ? 'selected' : '' }}>Médico</option>
                <option value="recepcao" {{ old('perfil', $user->perfil) == 'recepcao' ? 'selected' : '' }}>Recepção</option>
            </select>
            @error('perfil') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Senha (opcional)</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Confirmar Senha</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-primary">Atualizar Usuário</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection