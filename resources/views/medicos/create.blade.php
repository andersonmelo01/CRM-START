@extends('layouts.app')

@section('titulo', 'Cadastrar Médico')

@section('conteudo')

<div class="card">
    <div class="card-header bg-dark text-white">Novo Médico</div>

    <div class="card-body">
        <form method="POST" action="{{ route('medicos.store') }}">
            @csrf

            <div class="mb-3">
                <label>Nome</label>
                <input name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>CRM</label>
                <input name="crm" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Especialidade</label>
                <input name="especialidade" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Telefone</label>
                <input name="telefone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input name="email" class="form-control">
            </div>

            <button class="btn btn-success">Salvar</button>
            <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection