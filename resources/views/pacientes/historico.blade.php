@extends('layouts.app')

@section('titulo','Histórico do Paciente')

@section('conteudo')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📋 Histórico do Paciente</h3>

        <a href="{{ route('pacientes.index') }}"
            class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    {{-- CARD PACIENTE --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">
            <h4 class="mb-1">{{ $paciente->nome }}</h4>
            <div class="text-muted">
                CPF: {{ $paciente->cpf ?? '—' }}
            </div>
        </div>
    </div>


    @forelse($consultas as $c)

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <span>
                Consulta —
                {{ \Carbon\Carbon::parse($c->data)->format('d/m/Y') }}
                {{ $c->hora }}
            </span>

            <span class="badge
            @if($c->status=='agendada') bg-warning
            @elseif($c->status=='atendida') bg-success
            @else bg-danger @endif">
                {{ ucfirst($c->status) }}
            </span>
        </div>


        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <strong>Médico</strong><br>
                    {{ $c->medico->nome }}
                </div>

                <div class="col-md-8">
                    <strong>Observações</strong><br>
                    {{ $c->observacoes ?? '—' }}
                </div>

            </div>

            {{-- PRONTUÁRIO --}}
            <h6 class="border-bottom pb-2 mb-3">
                📝 Prontuário
            </h6>

            @if($c->prontuario)

            <div class="row">

                <div class="col-md-4">
                    <strong>Queixa</strong>
                    <div>{{ $c->prontuario->queixa ?? '—' }}</div>
                </div>

                <div class="col-md-4">
                    <strong>Diagnóstico</strong>
                    <div>{{ $c->prontuario->diagnostico ?? '—' }}</div>
                </div>

                <div class="col-md-4">
                    <strong>Conduta</strong>
                    <div>{{ $c->prontuario->conduta ?? '—' }}</div>
                </div>

            </div>

            @else
            <div class="text-muted">Sem prontuário</div>
            @endif


            {{-- EXAMES --}}
            <h6 class="border-bottom pb-2 mt-4 mb-3">
                🧪 Exames
            </h6>

            @if($c->exames->count())

            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Exame</th>
                        <th>Status</th>
                        <th>Resultado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($c->exames as $ex)
                    <tr>
                        <td>{{ $ex->nome }}</td>
                        <td>{{ $ex->status }}</td>
                        <td>{{ $ex->resultado ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <div class="text-muted">Nenhum exame</div>
            @endif


            {{-- PAGAMENTO --}}
            <h6 class="border-bottom pb-2 mt-4 mb-3">
                💰 Pagamento
            </h6>

            @if($c->pagamento)
            <div class="row">

                <div class="col-md-3">
                    Valor: R$ {{ number_format($c->pagamento->valor,2,',','.') }}
                </div>

                <div class="col-md-3">
                    Pago: R$ {{ number_format($c->pagamento->valor_pago ?? 0,2,',','.') }}
                </div>

                <div class="col-md-3">
                    Forma: {{ $c->pagamento->forma_pagamento }}
                </div>

                <div class="col-md-3">
                    Status:
                    <span class="badge
                        {{ $c->pagamento->status=='pago'
                           ? 'bg-success'
                           : 'bg-warning text-dark' }}">
                        {{ $c->pagamento->status }}
                    </span>
                </div>

            </div>
            @else
            <div class="text-muted">Sem registro de pagamento</div>
            @endif


            {{-- AÇÕES --}}
            <div class="mt-3">

                @if($c->prontuario)
                <a href="{{ route('prontuarios.show', $c) }}"
                    class="btn btn-sm btn-outline-primary">
                    Ver Prontuário
                </a>
                @endif

                <a href="{{ route('consultas.exames', $c->id) }}"
                    class="btn btn-sm btn-outline-info">
                    Exames
                </a>
            </div>

        </div>
    </div>

    @empty

    <div class="alert alert-info">
        Nenhuma consulta registrada para este paciente.
    </div>

    @endforelse

</div>
@endsection