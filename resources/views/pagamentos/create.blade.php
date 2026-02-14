@extends('layouts.app')

@section('titulo', 'Criar Pagamento')

@section('conteudo')
<div class="container py-4" style="max-width: 600px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-cash-stack me-2"></i>Novo Pagamento</h4>
        <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm p-4 rounded-4">
        <form method="POST" action="{{ route('pagamentos.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Consulta</label>
                <select name="consulta_id" class="form-select">
                    @foreach($consultas as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->paciente->nome }} - {{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Valor</label>
                <input type="number" name="valor" class="form-control" placeholder="Valor do pagamento" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Forma de Pagamento</label>
                <select name="forma_pagamento" class="form-select" required>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartao">Cartão</option>
                    <option value="pix">PIX</option>
                    <option value="convenio">Convênio</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="pago">Pago</option>
                    <option value="pendente">Pendente</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection