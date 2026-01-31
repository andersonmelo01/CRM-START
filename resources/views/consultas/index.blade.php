@extends('layouts.app')

@section('titulo', 'Consultas')

@section('conteudo')
<div class="container">

    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Agenda do Dia</h3>

        <div class="d-flex gap-2">
            <a href="{{ route('consultas.create') }}" class="btn btn-success">
                Nova Consulta
            </a>
            <a href="/bloqueios" class="btn btn-outline-secondary">
                Bloqueios
            </a>
        </div>
    </div>

    {{-- FILTRO --}}
    <form method="GET"
        action="{{ route('consultas.index') }}"
        class="mb-3 d-flex gap-2">

        <input type="date"
            name="data"
            value="{{ $data }}"
            class="form-control w-25">

        <button class="btn btn-primary">
            Filtrar
        </button>

    </form>

    {{-- TABELA --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Status</th>
                        <th>Prontuário</th>
                        <th>Exames</th>
                        <th>Observações</th>
                        <th>Pagamento</th>
                        <th width="160">Alterar Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($consultas as $c)
                    @php
                    $pagamento = $c->pagamento;
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $c->hora }}</td>

                        <td>{{ $c->paciente->nome }}</td>

                        <td>{{ $c->medico->nome }}</td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge
            @if($c->status=='agendada') bg-warning
            @elseif($c->status=='atendida') bg-success
            @else bg-danger @endif">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>

                        {{-- PRONTUÁRIO --}}
                        <td>
                            @if($c->prontuario)
                            <a href="{{ route('prontuarios.show',$c) }}"
                                class="btn btn-info btn-sm">
                                Ver
                            </a>
                            @else
                            <a href="{{ route('prontuarios.create',$c) }}"
                                class="btn btn-primary btn-sm">
                                Iniciar
                            </a>
                            @endif
                        </td>

                        {{-- EXAMES / RETORNO --}}
                        <td>
                            <a href="{{ route('consultas.exames',$c) }}"
                                class="btn btn-outline-dark btn-sm">
                                Exames
                            </a>
                        </td>

                        {{-- OBS --}}
                        <td class="text-muted small">
                            {{ $c->observacoes }}
                        </td>

                        {{-- PAGAMENTO --}}
                        <td>

                            @if($pagamento && $pagamento->status=='pendente')

                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#receberPagamentoModal{{ $pagamento->id }}">
                                Pendente
                            </button>

                            @elseif($pagamento && $pagamento->status=='pago')

                            <span class="badge bg-success">Pago</span>

                            <a href="{{ route('pagamentos.recibo',$pagamento) }}"
                                target="_blank"
                                class="btn btn-outline-primary btn-sm ms-1">
                                Recibo
                            </a>

                            @else
                            <span class="badge bg-secondary">
                                Sem pagamento
                            </span>
                            @endif

                        </td>

                        {{-- ALTERAR STATUS --}}
                        <td>
                            <form method="POST"
                                action="{{ route('consultas.update',$c) }}">
                                @csrf
                                @method('PUT')

                                <select name="status"
                                    class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="agendada" @selected($c->status=='agendada')>Agendada</option>
                                    <option value="atendida" @selected($c->status=='atendida')>Atendida</option>
                                    <option value="cancelada" @selected($c->status=='cancelada')>Cancelada</option>
                                </select>
                            </form>
                        </td>

                    </tr>

                    {{-- MODAL PAGAMENTO EMBUTIDO --}}
                    @if($pagamento)
                    <div class="modal fade"
                        id="receberPagamentoModal{{ $pagamento->id }}"
                        tabindex="-1">

                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="POST"
                                    action="{{ route('pagamentos.receber',$pagamento) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Receber Pagamento
                                        </h5>
                                        <button class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <p><strong>Paciente:</strong>
                                            {{ $pagamento->consulta->paciente->nome }}
                                        </p>

                                        <div class="mb-3">
                                            <label>Valor Consulta</label>
                                            <input type="number"
                                                step="0.01"
                                                name="valor"
                                                class="form-control"
                                                value="{{ $pagamento->valor }}">
                                        </div>

                                        <div class="mb-3">
                                            <label>Já Pago</label>
                                            <input class="form-control"
                                                value="{{ $pagamento->valor_pago ?? 0 }}"
                                                readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Receber Agora</label>
                                            <input type="number"
                                                step="0.01"
                                                name="valor_pago"
                                                class="form-control"
                                                required>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary"
                                            data-bs-dismiss="modal">
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

                    @empty
                    <tr>
                        <td colspan="9"
                            class="text-center text-muted py-4">
                            Nenhuma consulta nesta data
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection