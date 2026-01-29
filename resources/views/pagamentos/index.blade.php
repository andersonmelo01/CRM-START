@extends('layouts.app')

@section('titulo', 'Pagamento')

@section('conteudo')

<h4>Financeiro</h4>

<a href="{{ route('pagamentos.create') }}" class="btn btn-success mb-2">
    Novo Pagamento
</a>

<table class="table table-bordered">
    <tr>
        <th>Paciente</th>
        <th>Valor</th>
        <th>Forma</th>
        <th>Status</th>
        <th>Pagamento</th>
        <th>Data</th>
    </tr>

    @foreach($pagamentos as $p)

    <tr>
        <td>{{ $p->consulta->paciente->nome }}</td>
        <td>R$ {{ number_format($p->valor,2,',','.') }}</td>
        <td>{{ ucfirst($p->forma_pagamento) }}</td>
        <td>
            <span class="badge bg-{{ $p->status == 'pago' ? 'success' : 'warning' }}">
                {{ strtoupper($p->status) }}
            </span>
        </td>
        <!--Status Pagamento-->
        <td>
            @if($p->status === 'pendente')
            <button
                class="btn bg-warning  btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#receberPagamentoModal{{ $p->id }}">
                PENDENTE
            </button>
            @elseif($p->status === 'pago')
            <span class="badge bg-success">PAGO</span>
            @else
            <span class="badge bg-warning text-dark">SEM PAGAMENTO</span>
            @endif
            @if($p->status === 'pago')
            <a href="{{ route('pagamentos.recibo', $p) }}"
                target="_blank"
                class="btn btn-outline-primary btn-sm">
                🧾 Recibo
            </a>
            @endif
        </td>
        <!--Modal de Pagamento-->
        @if($p)
        <div
            class="modal fade"
            id="receberPagamentoModal{{ $p->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="{{ route('pagamentos.receber', $p) }}">
                        @csrf
                        @method('PATCH')

                        <div class="modal-header">
                            <h5 class="modal-title">Receber Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p><strong>Paciente:</strong> {{ $p->consulta->paciente->nome }}</p>

                            <div class="mb-3">
                                <label>Valor da Consulta</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="valor"
                                    class="form-control"
                                    value="{{ $p->valor }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Valor Já Pago</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    value="{{ $p->valor_pago ?? 0 }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label>Valor Recebido Agora</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="valor_pago"
                                    class="form-control"
                                    placeholder="Digite o valor"
                                    required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button class="btn btn-success">
                                Confirmar
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        @endif
        <td>{{ $p->data_pagamento }}</td>
    </tr>
    @endforeach
</table>
@endsection