@extends('layouts.app')

@section('titulo', 'Emitente')

@section('conteudo')

<h4>Dados do Estabelecimento</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('emitente.store') }}">
    @csrf
    <input type="hidden" name="id" value="{{ $emitente->id ?? '' }}">

    <div class="row">
        <div class="col-md-6">
            <label>Nome / Razão Social</label>
            <input type="text" name="nome" class="form-control"
                value="{{ $emitente->nome ?? '' }}" required>
        </div>

        <div class="col-md-6">
            <label>CPF / CNPJ</label>
            <input type="text" name="documento" class="form-control"
                value="{{ $emitente->documento ?? '' }}" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control"
                value="{{ $emitente->telefone ?? '' }}">
        </div>

        <div class="col-md-6">
            <label>Endereço</label>
            <input type="text" name="endereco" class="form-control"
                value="{{ $emitente->endereco ?? '' }}">
        </div>

        <div class="col-md-2">
            <label>UF</label>
            <input type="text" name="uf" class="form-control"
                value="{{ $emitente->uf ?? '' }}">
        </div>
    </div>

    <div class="mt-2">
        <label>Cidade</label>
        <input type="text" name="cidade" class="form-control"
            value="{{ $emitente->cidade ?? '' }}">
    </div>

    <div class="mt-2">
        <label>Mensagem no rodapé do recibo</label>
        <input type="text" name="mensagem_rodape" class="form-control"
            value="{{ $emitente->mensagem_rodape ?? 'Obrigado pela preferência!' }}">
    </div>

    <button class="btn btn-primary mt-3">
        Salvar Emitente
    </button>
</form>

@endsection