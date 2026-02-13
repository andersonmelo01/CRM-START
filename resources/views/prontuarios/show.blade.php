@extends('layouts.app')

@section('titulo', 'Prontuário')

@section('conteudo')

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-clipboard2-pulse me-2"></i>
                Prontuário Médico
            </h4>

            <span class="badge bg-light text-dark">
                Consulta #{{ $consulta->id }}
            </span>
        </div>

        <div class="card-body">

            {{-- Dados da Consulta --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="text-muted small">Paciente</label>
                    <div class="fw-semibold fs-5">
                        {{ $consulta->paciente?->nome ?? 'Não informado' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="text-muted small">Médico</label>
                    <div class="fw-semibold fs-5">
                        {{ $consulta->medico?->nome ?? 'Não informado' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="text-muted small">Data / Hora</label>
                    <div class="fw-semibold fs-5">
                        {{ $consulta->data }} {{ $consulta->hora }}
                    </div>
                </div>
            </div>

            <hr>
            {{-- Obs de entrada --}}
            <div class="mb-4">
                <h6 class="text-primary mb-2">
                    <i class="bi bi-chat-left-text me-1"></i>
                    Deu entrada queixando-se de
                </h6>

                <div class="border rounded p-3 bg-light">
                    {{ $consulta?->observacoes ?? 'Não informado' }}
                </div>
            </div>
            {{-- Queixa --}}
            <div class="mb-4">
                <h6 class="text-primary mb-2">
                    <i class="bi bi-chat-left-text me-1"></i>
                    Queixa Principal
                </h6>

                <div class="border rounded p-3 bg-light">
                    {{ $consulta->prontuario?->queixa_principal ?? 'Não informado' }}
                </div>
            </div>

            {{-- Diagnóstico --}}
            <div class="mb-4">
                <h6 class="text-success mb-2">
                    <i class="bi bi-activity me-1"></i>
                    Diagnóstico
                </h6>

                <div class="border rounded p-3 bg-light">
                    {{ $consulta->prontuario?->diagnostico ?? 'Não informado' }}
                </div>
            </div>

            {{-- Conduta --}}
            <div class="mb-4">
                <h6 class="text-warning mb-2">
                    <i class="bi bi-journal-medical me-1"></i>
                    Conduta
                </h6>

                <div class="border rounded p-3 bg-light">
                    {{ $consulta->prontuario?->conduta ?? 'Não informado' }}
                </div>
            </div>

            {{-- Prescrição --}}
            <div class="mb-4">
                <h6 class="text-success mb-2">
                    <i class="bi bi-chat-left-text me-1"></i>
                    Prescrição
                </h6>

                <div class="border rounded p-3 bg-light">
                    {{ $consulta->prontuario?->prescricao ?? 'Não informado' }}
                </div>
            </div>
        </div>

        <div class="card-footer text-end d-flex justify-content-between">
            <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>

            {{-- Botão para abrir a modal do prontuário completo --}}
            <button class="btn btn-info"
                data-bs-toggle="modal"
                data-bs-target="#modalProntuario">
                <i class="bi bi-eye"></i>
                Ver Prontuário Completo
            </button>
        </div>
    </div>

    <!--Listar exames-->
    <table class="table mt-3" id="tabelaExames">
        <tr>
            <th>Tipo</th>
            <th>Status</th>
            <th>Data</th>
            <th>Resultado</th>
            <th width="120">Imprimir</th>
        </tr>

        @foreach($consulta->exames as $ex)
        <tr>
            <td>{{ $ex->tipo_exame }}</td>
            <td>{{ $ex->status }}</td>
            <td>{{ $ex->data_solicitacao }}</td>
            <td>
                @if(!$ex->resultado)

                <form method="POST" action="{{ route('exames.resultado',$ex) }}">
                    @csrf
                    @method('PUT')
                    <textarea name="resultado" class="form-control"></textarea>
                    <button class="btn btn-sm btn-success mt-1">Salvar</button>
                </form>

                @else
                {{ $ex->resultado }}
                @endif
            </td>
            <td>
                <a href="{{ route('exames.imprimir', $ex) }}"
                    target="_blank"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </table>

    <!--Pedido de exame-->
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-outline-primary" onclick="imprimirExames('tabelaExames')">
            <i class="bi bi-printer"></i> Imprimir Exames
        </button>
    </div>

    <hr>
    <h5 class="mt-4">Pedidos de Exame</h5>

    <form method="POST" action="{{ route('exames.store',$consulta->id) }}">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <label>Tipo</label>
                <select name="tipo_exame" class="form-select">
                    <option value="">Selecione...</option>

                    <optgroup label="Laboratoriais">
                        <option>Hemograma Completo</option>
                        <option>Glicemia</option>
                        <option>Colesterol Total</option>
                        <option>HDL</option>
                        <option>LDL</option>
                        <option>Triglicerídeos</option>
                        <option>TSH</option>
                        <option>T4 Livre</option>
                        <option>Urina Tipo I</option>
                        <option>Creatinina</option>
                        <option>Ureia</option>
                    </optgroup>

                    <optgroup label="Imagem">
                        <option>Raio-X</option>
                        <option>Ultrassom</option>
                        <option>Ressonância</option>
                        <option>Tomografia</option>
                    </optgroup>

                    <optgroup label="Cardiológicos">
                        <option>Eletrocardiograma</option>
                        <option>Ecocardiograma</option>
                    </optgroup>

                    <option value="outro">Outro exame...</option>
                </select>
            </div>

            <div class="col-md-8">
                <label>Descrição</label>
                <textarea name="descricao" rows="3" class="form-control"></textarea>
            </div>
        </div>

        <button class="btn btn-warning mt-3">
            Solicitar Exame
        </button>

    </form>

</div>

<!-- MODAL PRONTUÁRIO COMPLETO -->
<div class="modal fade" id="modalProntuario" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Cabeçalho -->
            <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    Prontuário Completo — Consulta #{{ $consulta->id }}
                </h5>

                <div>
                    <button class="btn btn-light btn-sm me-2" onclick="imprimirModal('modalProntuarioBody')">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <!-- CORPO DA MODAL: adicionei o id aqui -->
            <div class="modal-body" id="modalProntuarioBody">

                <!-- Conteúdo do prontuário -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="text-muted">Paciente</label>
                        <div class="fw-bold">{{ $consulta->paciente?->nome }}</div>
                        <div>{{ $consulta->paciente?->cpf }}</div>
                        <div>{{ $consulta->paciente?->telefone }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted">Médico</label>
                        <div class="fw-bold">{{ $consulta->medico?->nome }}</div>
                        <div>{{ $consulta->medico?->crm }}</div>
                        <div>{{ $consulta->medico?->especialidade }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted">Data / Hora</label>
                        <div class="fw-bold">{{ $consulta->data }} {{ $consulta->hora }}</div>
                        <div>Status: {{ $consulta->status ?? '—' }}</div>
                    </div>
                </div>

                <hr>

                <h6 class="text-danger">Deu entrada queixando-se</h6>
                <div class="border p-3 rounded bg-light mb-3">
                    {{ $consulta?->observacoes ?? 'Não informado' }}
                </div>

                <h6 class="text-primary">Queixa Principal</h6>
                <div class="border p-3 rounded bg-light mb-3">
                    {{ $consulta->prontuario?->queixa_principal ?? 'Não informado' }}
                </div>

                <h6 class="text-danger">Diagnóstico</h6>
                <div class="border p-3 rounded bg-light mb-3">
                    {{ $consulta->prontuario?->diagnostico ?? 'Não informado' }}
                </div>

                <h6 class="text-warning">Conduta</h6>
                <div class="border p-3 rounded bg-light mb-3">
                    {{ $consulta->prontuario?->conduta ?? 'Não informado' }}
                </div>

                <h6 class="text-success">Prescrição</h6>
                <div class="border p-3 rounded bg-light mb-4">
                    {{ $consulta->prontuario?->prescricao ?? 'Não informado' }}
                </div>

                <h5 class="mt-4">Exames Vinculados</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consulta->exames as $ex)
                        <tr>
                            <td>{{ $ex->tipo_exame }}</td>
                            <td>{{ $ex->status }}</td>
                            <td>{{ $ex->data_solicitacao }}</td>
                            <td>{{ $ex->resultado ?? 'Pendente' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Nenhum exame solicitado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<!-- Script para imprimir modal com ID da consulta e prontuário -->
<script>
    function imprimirModal(id, consultaId, prontuarioId) {
        var conteudo = document.getElementById(id).innerHTML;

        var janela = window.open('', '_blank', 'width=900,height=700');

        janela.document.write(`
        <html>
        <head>
            <title>Prontuário Médico</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

            <style>
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    background: #ffffff;
                    padding: 30px;
                    color: #000;
                }

                .print-container {
                    max-width: 800px;
                    margin: auto;
                }

                .cabecalho {
                    border-bottom: 2px solid #0d6efd;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                }

                .cabecalho h2 {
                    margin: 0;
                    font-weight: bold;
                    color: #0d6efd;
                }

                .cabecalho small {
                    color: #555;
                }

                .info-topo {
                    display: flex;
                    justify-content: space-between;
                    font-size: 14px;
                    margin-top: 10px;
                }

                .titulo-secao {
                    background: #f1f5f9;
                    padding: 8px 12px;
                    border-left: 4px solid #0d6efd;
                    font-weight: bold;
                    margin-top: 20px;
                    margin-bottom: 10px;
                }

                .box {
                    border: 1px solid #dee2e6;
                    border-radius: 6px;
                    padding: 15px;
                    margin-bottom: 15px;
                }

                .assinatura {
                    margin-top: 60px;
                    text-align: center;
                }

                .assinatura .linha {
                    border-top: 1px solid #000;
                    width: 300px;
                    margin: 0 auto 5px auto;
                }

                .no-print {
                    display: none !important;
                }

                .page-break {
                    page-break-before: always;
                }

                @media print {
                    body {
                        padding: 0;
                    }
                }
            </style>
        </head>

        <body>
            <div class="print-container">

                <!-- Cabeçalho -->
                <div class="cabecalho">
                    <h2>Clínica Médica</h2>
                    <small>Prontuário do Paciente</small>

                    <div class="info-topo">
                        <div><strong>ID da Consulta:</strong> ${consultaId}</div>
                        <div><strong>Prontuário:</strong> ${prontuarioId}</div>
                    </div>
                </div>

                ${conteudo}

                <!-- Assinatura -->
                <div class="assinatura">
                    <div class="linha"></div>
                    <small>Assinatura do Profissional Responsável</small>
                </div>

            </div>
        </body>
        </html>
        `);

        janela.document.close();
        janela.focus();

        setTimeout(() => {
            janela.print();
            janela.close();
        }, 500);
    }
</script>

<!-- Script para imprimir Exame -->
<script>
    function imprimirExames(id) {
        var conteudo = document.getElementById(id).outerHTML;
        var janela = window.open('', '_blank', 'width=900,height=700');

        janela.document.write(`
        <html>
        <head>
            <title>Exames da Consulta</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; }
                h3 { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h3>Exames da Consulta #{{ $consulta->id }}</h3>
            ${conteudo}
        </body>
        </html>
    `);

        janela.document.close();
        janela.print();
    }
</script>

@endsection