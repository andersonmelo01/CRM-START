@extends('layouts.app')

@section('titulo', 'Pagamento')

@section('conteudo')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2"></i>Financeiro</h4>
        <a href="{{ route('pagamentos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Novo Pagamento
        </a>
    </div>

    {{-- Filtro --}}
    <div class="card shadow-sm mb-4 p-3 rounded-4">
        <form method="GET" action="{{ route('pagamentos.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filtrar por Data</label>
                <input type="date" name="data" class="form-control" value="{{ request('data', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-clockwise"></i> Hoje
                </a>
            </div>
        </form>
    </div>

    {{-- Tabela de Pagamentos --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Paciente</th>
                        <th>Valor</th>
                        <th>Forma</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Data</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pagamentos as $p)
                    <tr>
                        <td>{{ $p->consulta->paciente->nome }}</td>
                        <td>R$ {{ number_format($p->valor, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($p->forma_pagamento) }}</td>

                        {{-- Status --}}
                        <td>
                            <span class="badge bg-{{ $p->status === 'pago' ? 'success' : 'warning' }}">
                                {{ strtoupper($p->status) }}
                            </span>
                        </td>

                        {{-- Botão Pagamento --}}
                        <td>
                            @if($p->status === 'pendente')
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#receberPagamentoModal{{ $p->id }}">
                                PENDENTE
                            </button>
                            @elseif($p->status === 'pago')
                            <span class="badge bg-success">PAGO</span>
                            <a href="{{ route('pagamentos.recibo', $p) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm ms-1">
                                🧾 Recibo
                            </a>
                            @else
                            <span class="badge bg-secondary text-dark">SEM PAGAMENTO</span>
                            @endif
                        </td>

                        <td>{{ \Carbon\Carbon::parse($p->data_pagamento)->format('d/m/Y') }}</td>
                    </tr>

                    {{-- Modal Receber Pagamento --}}
                    <div class="modal fade" id="receberPagamentoModal{{ $p->id }}" tabindex="-1">
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
                                            <input type="number" step="0.01" name="valor" class="form-control"
                                                value="{{ $p->valor }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Valor Já Pago</label>
                                            <input type="number" class="form-control"
                                                value="{{ $p->valor_pago ?? 0 }}" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Valor Recebido Agora</label>
                                            <input type="number" step="0.01" name="valor_pago" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button class="btn btn-success">Confirmar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                            Nenhum pagamento encontrado para esta data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection