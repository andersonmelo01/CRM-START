@extends('layouts.app')

@section('titulo', 'Novo Protuario')

@section('conteudo')
<div class="container">
    <h3>Prontuário Médico</h3>

    <div class="card mb-3">
        <div class="card-body">
            <strong>Paciente:</strong> {{ $consulta->paciente->nome }} <br>
            <strong>Médico:</strong> {{ $consulta->medico->nome }} <br>
            <strong>Data:</strong> {{ $consulta->data }} - {{ $consulta->hora }} <br>
            <strong>Status Pagamento:</strong>
            @if($consulta->pagamento->valor_pago >= $consulta->pagamento->valor)
            {{ $consulta->pagamento->status }} - R$ {{ number_format($consulta->pagamento->valor, 2, ',', '.')}}<br>
            @else
            @if($consulta->pagamento)
            {{ $consulta->pagamento->status }} - R$ {{ number_format($consulta->pagamento->valor, 2, ',', '.') }}<br>
            @else
            <span class="text-danger">Não pago</span><br>
            @endif
            <strong>Pagamento Parcelado ou Integral:</strong><br>
            @if($consulta->pagamento)
            R$ {{ number_format($consulta->pagamento->valor_pago, 2, ',', '.') }}
            @else
            <span class="text-danger">Não pago</span>
            @endif
            @endif

        </div>
    </div>

    <form method="POST" action="{{ route('prontuarios.store', $consulta->id) }}">
        @csrf

        <div class="mb-3">
            <label>Queixa Principal</label>
            <textarea name="queixa_principal" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>História da Doença Atual</label>
            <textarea name="historico_doenca" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Exame Físico</label>
            <textarea name="exame_fisico" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Diagnóstico</label>
            <textarea name="diagnostico" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Conduta</label>
            <textarea name="conduta" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Prescrição</label>
            <textarea name="prescricao" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Salvar Prontuário</button>
        <a href="{{ route('consultas.index') }}" class="btn btn-secondary">
            Voltar
        </a>
    </form>
</div>
@endsection