@extends('layouts.app')

@section('titulo', 'Criar Pagamento')

@section('conteudo')

<h4>Novo Pagamento</h4>

<form method="POST" action="{{ route('pagamentos.store') }}">
    @csrf

    <select name="consulta_id" class="form-control mb-2">
        @foreach($consultas as $c)
        <option value="{{ $c->id }}">
            {{ $c->paciente->nome }} - {{ $c->data }}
        </option>
        @endforeach
    </select>

    <input type="number" name="valor" class="form-control mb-2" placeholder="Valor">

    <select name="forma_pagamento" class="form-control mb-2">
        <option value="dinheiro">Dinheiro</option>
        <option value="cartao">Cartão</option>
        <option value="pix">PIX</option>
        <option value="convenio">Convênio</option>
    </select>

    <select name="status" class="form-control mb-2">
        <option value="pago">Pago</option>
        <option value="pendente">Pendente</option>
    </select>

    <button class="btn btn-primary">Salvar</button>
</form>

@endsection