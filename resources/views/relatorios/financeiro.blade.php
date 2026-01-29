@extends('layouts.app')

@section('titulo', 'Relatório Financeiro')

@section('conteudo')

<h4>Relatório Financeiro</h4>

<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <label>Data Inicial</label>
        <input type="date" name="data_inicio" class="form-control" value="{{ $inicio }}">
    </div>

    <div class="col-md-3">
        <label>Data Final</label>
        <input type="date" name="data_fim" class="form-control" value="{{ $fim }}">
    </div>

    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Filtrar</button>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-bg-secondary">
            <div class="card-body">
                <strong>Total Faturado</strong><br>
                R$ {{ number_format($totalFaturado, 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <strong>Total Recebido</strong><br>
                R$ {{ number_format($totalRecebido, 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <strong>Total Pendente</strong><br>
                R$ {{ number_format($totalPendente, 2, ',', '.') }}
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th>Recibo</th>
            <th>Paciente</th>
            <th>Valor</th>
            <th>Pago</th>
            <th>Status</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagamentos as $p)
        <tr>
            <td>{{ $p->numero_recibo ?? '-' }}</td>
            <td>{{ $p->consulta->paciente->nome }}</td>
            <td>R$ {{ number_format($p->valor, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($p->valor_pago, 2, ',', '.') }}</td>
            <td>
                @if($p->status === 'pago')
                <span class="badge bg-success">Pago</span>
                @else
                <span class="badge bg-warning text-dark">Pendente</span>
                @endif
            </td>
            <td>{{ $p->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
        <button onclick="window.print()" class="btn btn-success mb-3 no-print">
            Imprimir Relatório
        </button>

    </tbody>
</table>

@endsection