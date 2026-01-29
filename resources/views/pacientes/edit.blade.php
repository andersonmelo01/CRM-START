@extends('layouts.app')

@section('titulo', 'Editar Paciente')

@section('conteudo')

<div class="card">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Editar Paciente</h5>
    </div>

    <div class="card-body">

        {{-- Mensagens de erro --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('pacientes.update', $paciente) }}">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome" class="form-control"
                        value="{{ old('nome', $paciente->nome) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="form-control"
                        value="{{ old('data_nascimento', $paciente->data_nascimento) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control"
                        value="{{ old('cpf', $paciente->cpf) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control"
                        value="{{ old('telefone', $paciente->telefone) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $paciente->email) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="M" {{ $paciente->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ $paciente->sexo == 'F' ? 'selected' : '' }}>Feminino</option>
                        <option value="O" {{ $paciente->sexo == 'O' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <textarea name="endereco" class="form-control" rows="2">{{ old('endereco', $paciente->endereco) }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-warning">
                    Atualizar Paciente
                </button>
            </div>

        </form>
    </div>
</div>

@endsection