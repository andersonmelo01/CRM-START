@extends('layouts.app')

@section('titulo', 'Consultas')

@section('conteudo')
<div class="container-fluid py-3">

    {{-- ALERTAS --}}
    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- CABEÇALHO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                <i class="bi bi-calendar-week text-primary me-2"></i>
                Agenda de Consultas
            </h4>
            <small class="text-muted">
                Gerenciamento diário de atendimentos
            </small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('consultas.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Nova Consulta
            </a>
        </div>
    </div>

    {{-- FILTRO --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET"
                action="{{ route('consultas.index') }}"
                class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Data</label>
                    <input type="date"
                        name="data"
                        value="{{ $data }}"
                        class="form-control">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Status</th>
                        <th>Prontuário</th>
                        <th>Exames</th>
                        <th>Observações</th>
                        <th>Pagamento</th>
                        <th>WhatsApp</th>
                        <th width="170">Status Consulta</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($consultas as $c)
                    @php
                    $pagamento = $c->pagamento;
                    $bloqueado = in_array($c->status, ['pre-cadastro','cancelada']);
                    @endphp

                    <tr class="@if($bloqueado) table-warning @endif">
                        <td class="fw-bold text-primary">{{ $c->hora }}</td>

                        <td>
                            <i class="bi bi-person me-1 text-muted"></i>
                            {{ $c->paciente->nome }}
                        </td>

                        <td>
                            <i class="bi bi-person-badge me-1 text-muted"></i>
                            {{ $c->medico->nome }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge
                                @if($c->status == 'pre-cadastro') bg-secondary
                                @elseif($c->status == 'agendada') bg-warning
                                @elseif($c->status == 'atendida') bg-success
                                @elseif($c->status == 'cancelada') bg-danger
                                @endif">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>

                        {{-- PRONTUÁRIO --}}
                        <td>
                            @if(!$bloqueado)
                            @if($c->prontuario)
                            <a href="{{ route('prontuarios.show',$c) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            @else
                            <a href="{{ route('prontuarios.create',$c) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-file-earmark-plus"></i>
                            </a>
                            @endif
                            @else
                            <span class="badge bg-secondary">Bloqueado</span>
                            @endif
                        </td>

                        {{-- EXAMES --}}
                        <td>
                            @if(!$bloqueado)
                            <a href="{{ route('consultas.exames',$c) }}" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Bloqueado</span>
                            @endif
                        </td>

                        {{-- OBSERVAÇÕES --}}
                        <td class="text-muted small" style="max-width:200px">
                            {{ Str::limit($c->observacoes, 50) }}
                        </td>

                        {{-- PAGAMENTO --}}
                        <td>
                            @if($pagamento && $pagamento->status=='pendente')
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#receberPagamentoModal{{ $pagamento->id }}">
                                <i class="bi bi-cash"></i>
                                Pendente
                            </button>
                            @elseif($pagamento && $pagamento->status=='pago')
                            <span class="badge bg-success">Pago</span>
                            <a href="{{ route('pagamentos.recibo',$pagamento) }}"
                                target="_blank"
                                class="btn btn-outline-primary btn-sm ms-1">
                                <i class="bi bi-receipt"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Sem pagamento</span>
                            @endif
                        </td>

                        {{-- WHATSAPP --}}
                        <td>
                            @if(!$bloqueado)
                            @if($c->paciente && $c->paciente->telefone)
                            <a href="{{ route('consultas.whatsapp', $c) }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Sem telefone</span>
                            @endif
                            @else
                            @if($c->paciente && $c->paciente->telefone)
                            <a href="{{ route('consultas.whatsappPreCadastroConsulta', $c) }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Sem telefone</span>
                            @endif
                            @endif
                        </td>

                        {{-- ALTERAR STATUS --}}
                        <td>
                            @php
                            $user = auth()->user();
                            $podeAlterar = in_array($user->perfil, ['admin','recepcao']);
                            @endphp

                            @if($podeAlterar)
                            <form method="POST" action="{{ route('consultas.update',$c) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="pre-cadastro" @selected($c->status=='pre-cadastro')>Pré-Cadastro</option>
                                    <option value="agendada" @selected($c->status=='agendada')>Agendada</option>
                                    <option value="atendida" @selected($c->status=='atendida')>Atendida</option>
                                    <option value="cancelada" @selected($c->status=='cancelada')>Cancelada</option>
                                </select>
                            </form>
                            @else
                            <span class="badge bg-secondary">Sem permissão</span>
                            @endif
                        </td>
                    </tr>

                    {{-- MODAL PAGAMENTO --}}
                    @if($pagamento)
                    <div class="modal fade" id="receberPagamentoModal{{ $pagamento->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('pagamentos.receber',$pagamento) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-cash-coin me-2"></i>
                                            Receber Pagamento
                                        </h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p><strong>Paciente:</strong> {{ $pagamento->consulta->paciente->nome }}</p>

                                        <div class="mb-3">
                                            <label>Valor Consulta</label>
                                            <input type="number" step="0.01" name="valor" class="form-control" value="{{ $pagamento->valor }}">
                                        </div>

                                        <div class="mb-3">
                                            <label>Já Pago</label>
                                            <input class="form-control" value="{{ $pagamento->valor_pago ?? 0 }}" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Receber Agora</label>
                                            <input type="number" step="0.01" name="valor_pago" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button class="btn btn-success">Confirmar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                            Nenhuma consulta encontrada para esta data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection