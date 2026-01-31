@extends('layouts.app')

@section('titulo', 'Novo Prontuário')

@section('conteudo')

<div class="container py-4">

    {{-- CABEÇALHO --}}
    <div class="mb-4">
        <h4 class="fw-bold">
            <i class="bi bi-clipboard2-pulse text-primary me-2"></i>
            Novo Prontuário Médico
        </h4>
        <p class="text-muted mb-0">
            Registro clínico completo da consulta
        </p>
    </div>


    {{-- CARD DADOS DA CONSULTA --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-calendar-check me-2"></i>
            Dados da Consulta
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="text-muted small">Paciente</label>
                    <div class="fs-5 fw-semibold">
                        {{ $consulta->paciente->nome }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="text-muted small">Médico</label>
                    <div class="fs-5 fw-semibold">
                        {{ $consulta->medico->nome }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="text-muted small">Data / Hora</label>
                    <div class="fs-5 fw-semibold">
                        {{ $consulta->data }} — {{ $consulta->hora }}
                    </div>
                </div>

                {{-- PAGAMENTO --}}
                <div class="col-md-12">
                    <label class="text-muted small">Pagamento</label>

                    @if($consulta->pagamento)

                    @php
                    $p = $consulta->pagamento;
                    $quitado = $p->valor_pago >= $p->valor;
                    @endphp

                    <div class="mt-1">

                        <span class="badge {{ $quitado ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $p->status }}
                        </span>

                        <span class="ms-2">
                            Total: R$ {{ number_format($p->valor, 2, ',', '.') }}
                        </span>

                        <span class="ms-3">
                            Pago: R$ {{ number_format($p->valor_pago, 2, ',', '.') }}
                        </span>

                    </div>

                    @else
                    <span class="badge bg-danger">Não pago</span>
                    @endif
                </div>

            </div>
        </div>
    </div>


    {{-- FORMULÁRIO --}}
    <form method="POST" action="{{ route('prontuarios.store', $consulta->id) }}">
        @csrf

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-journal-medical me-2"></i>
                Registro Clínico
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Queixa Principal *</label>
                        <textarea name="queixa_principal" rows="3"
                            class="form-control @error('queixa_principal') is-invalid @enderror"
                            required></textarea>
                        @error('queixa_principal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">História da Doença Atual</label>
                        <textarea name="historico_doenca" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Exame Físico</label>
                        <textarea name="exame_fisico" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Diagnóstico *</label>
                        <textarea name="diagnostico" rows="3"
                            class="form-control @error('diagnostico') is-invalid @enderror"
                            required></textarea>
                        @error('diagnostico')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Conduta</label>
                        <textarea name="conduta" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Prescrição</label>
                        <textarea name="prescricao" rows="3" class="form-control"></textarea>
                    </div>

                </div>

            </div>

            <div class="card-footer text-end bg-white">

                <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <button class="btn btn-success">
                    <i class="bi bi-save"></i>
                    Salvar Prontuário
                </button>

            </div>
        </div>

    </form>

</div>

@endsection