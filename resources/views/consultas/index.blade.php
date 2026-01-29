@extends('layouts.app')

@section('titulo', 'Consultas')

@section('conteudo')
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h3 class="mb-3">Agenda do Dia</h3>

    <!-- FILTRO POR DATA -->
    <form method="GET" action="{{ route('consultas.index') }}" class="mb-3 d-flex gap-2">
        <input type="date"
            name="data"
            value="{{ $data }}"
            class="form-control w-25">

        <button class="btn btn-primary">Filtrar</button>

        <a href="{{ route('consultas.create') }}" class="btn btn-success">
            Nova Consulta
        </a>
        <a href="/bloqueios" class="btn btn-success">
            Bloqueios
        </a>
    </form>

    <!-- TABELA -->
    <table class="table table-bordered align-middle">
        <thead class="table-primary">
            <tr>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Status</th>
                <th>Consultar</th>
                <th>Observações</th>
                <th>Pagamento</th>
                <th width="180">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($consultas as $c)
            @php
            $pagamento = $c->pagamento;
            @endphp
            <tr>
                <td>{{ $c->hora }}</td>
                <td>{{ $c->paciente->nome }}</td>
                <td>{{ $c->medico->nome }}</td>
                <td>
                    <span class="badge
                            @if($c->status == 'agendada') bg-warning
                            @elseif($c->status == 'atendida') bg-success
                            @else bg-danger @endif">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>

                <!-- Prontuario-->
                <td class="text-center">
                    @if($c->prontuario)
                    <a href="{{ route('prontuarios.show', $c) }}"
                        class="btn btn-info btn-sm">
                        Ver Prontuário
                    </a>
                    @else
                    <a href="{{ route('prontuarios.create', $c) }}"
                        class="btn btn-primary btn-sm">
                        Iniciar Consulta
                    </a>
                    @endif
                </td>
                <td>{{ $c->observacoes }}</td>
                <!--Status Pagamento-->
                <td>
                    @if($c->pagamento && $c->pagamento->status === 'pendente')
                    <button
                        class="btn bg-warning  btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#receberPagamentoModal{{ $pagamento->id }}">
                        PENDENTE
                    </button>
                    @elseif($c->pagamento && $c->pagamento->status === 'pago')
                    <span class="badge bg-success">PAGO</span>
                    @else
                    <span class="badge bg-warning text-dark">SEM PAGAMENTO</span>
                    @endif
                    @if($pagamento->status === 'pago')
                    <a href="{{ route('pagamentos.recibo', $pagamento) }}"
                        target="_blank"
                        class="btn btn-outline-primary btn-sm">
                        🧾 Recibo
                    </a>
                    @endif

                </td>
                <!--Modal de Pagamento-->
                @if($pagamento)
                <div
                    class="modal fade"
                    id="receberPagamentoModal{{ $pagamento->id }}"
                    tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="{{ route('pagamentos.receber', $pagamento) }}">
                                @csrf
                                @method('PATCH')

                                <div class="modal-header">
                                    <h5 class="modal-title">Receber Pagamento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <p><strong>Paciente:</strong> {{ $pagamento->consulta->paciente->nome }}</p>

                                    <div class="mb-3">
                                        <label>Valor da Consulta</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="valor"
                                            class="form-control"
                                            value="{{ $pagamento->valor }}"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Valor Já Pago</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            value="{{ $pagamento->valor_pago ?? 0 }}"
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


                <!-- Prontuario-->
                <td>
                    <form method="POST"
                        action="{{ route('consultas.update', $c) }}">
                        @csrf
                        @method('PUT')

                        <select name="status"
                            class="form-select"
                            onchange="this.form.submit()">
                            <option value="agendada" @selected($c->status=='agendada')>Agendada</option>
                            <option value="atendida" @selected($c->status=='atendida')>Atendida</option>
                            <option value="cancelada" @selected($c->status=='cancelada')>Cancelada</option>
                        </select>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Nenhuma consulta para esta data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection