@extends('layouts.app')

@section('titulo', 'Cadastrar Paciente')

@section('conteudo')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Cadastrar Paciente</h5>
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

        <form method="POST" action="{{ route('pacientes.store') }}">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome" class="form-control"
                        value="{{ old('nome') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="form-control"
                        value="{{ old('data_nascimento') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control"
                        value="{{ old('cpf') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control"
                        value="{{ old('telefone') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                        <option value="O">Outro</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <textarea name="endereco" class="form-control" rows="2">{{ old('endereco') }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                    Voltar
                </a>
                <button type="submit" class="btn btn-success">
                    Salvar Paciente
                </button>
            </div>

        </form>
    </div>
</div>
@endsection