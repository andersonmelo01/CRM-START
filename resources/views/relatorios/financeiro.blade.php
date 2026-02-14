@extends('layouts.app')

@section('titulo', 'Relatório Financeiro')

@section('conteudo')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">📊 Relatório Financeiro</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Voltar
            </a>
            @if($pagamentos->count())
            <button onclick="window.print()" class="btn btn-success">
                <i class="bi bi-printer-fill me-1"></i> Imprimir
            </button>
            @endif
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" name="data_inicio" class="form-control" value="{{ $inicio }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data Final</label>
                    <input type="date" name="data_fim" class="form-control" value="{{ $fim }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Médico</label>
                    <select name="medico_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($medicos as $m)
                        <option value="{{ $m->id }}" @selected(request('medico_id')==$m->id)>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Especialidade</label>
                    <select name="especialidade" class="form-select">
                        <option value="">Todas</option>
                        @foreach($especialidades as $esp)
                        <option value="{{ $esp }}" @selected(request('especialidade')==$esp)>{{ $esp }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mt-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cards resumo --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card text-bg-secondary shadow-sm">
                <div class="card-body">
                    <strong>Total Faturado</strong><br>
                    <span class="fs-5">R$ {{ number_format($totalFaturado, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <strong>Total Recebido</strong><br>
                    <span class="fs-5">R$ {{ number_format($totalRecebido, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning shadow-sm">
                <div class="card-body">
                    <strong>Total Pendente</strong><br>
                    <span class="fs-5">R$ {{ number_format($totalPendente, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabela de pagamentos --}}
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Recibo</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Especialidade</th>
                        <th>Valor</th>
                        <th>Pago</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagamentos as $p)
                    <tr>
                        <td>{{ $p->numero_recibo ?? '-' }}</td>
                        <td>{{ $p->consulta->paciente->nome }}</td>
                        <td>{{ $p->consulta->medico->nome }}</td>
                        <td>{{ $p->consulta->medico->especialidade ?? '-' }}</td>
                        <td>R$ {{ number_format($p->valor, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($p->valor_pago ?? 0, 2, ',', '.') }}</td>
                        <td>
                            @if($p->status === 'pago')
                            <span class="badge bg-success">Pago</span>
                            @else
                            <span class="badge bg-warning text-dark">Pendente</span>
                            @endif
                        </td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Nenhum pagamento encontrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- CSS para impressão --}}
@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        table {
            font-size: 12pt;
        }

        table th,
        table td {
            border: 1px solid #333 !important;
        }
    }
</style>
@endpush

@endsection