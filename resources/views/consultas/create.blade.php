@extends('layouts.app')

@section('titulo', 'Nova Consulta')

@section('conteudo')

<div class="container py-4">

    {{-- CABEÇALHO --}}
    <div class="mb-4">
        <h4 class="fw-bold">
            <i class="bi bi-calendar-plus text-primary me-2"></i>
            Nova Consulta
        </h4>
        <p class="text-muted mb-0">
            Agendamento de consulta médica
        </p>
    </div>

    {{-- ERROS --}}
    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <strong>Corrija os erros abaixo:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form method="POST" action="{{ route('consultas.store') }}">
        @csrf

        {{-- ================= DADOS DA CONSULTA ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-clipboard-check me-2"></i>
                Dados da Consulta
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- PACIENTE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Paciente</label>

                        <div class="input-group">
                            <input type="text"
                                id="paciente_nome"
                                class="form-control"
                                placeholder="Selecione um paciente"
                                readonly required>

                            <input type="hidden" name="paciente_id" id="paciente_id">

                            <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPacientes">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    {{-- MÉDICO --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Médico</label>

                        <select name="medico_id" id="medicoSelect"
                            class="form-select" required>
                            <option value="">Selecione um médico</option>
                            @foreach($medicos as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                            @endforeach
                        </select>

                        <div id="avisoBloqueioMedico"
                            class="text-danger small mt-1"
                            style="display:none;">
                            ⚠️ Médico bloqueado no momento
                        </div>
                    </div>

                    {{-- DATA --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Data</label>
                        <input type="date" name="data" class="form-control" required>
                    </div>

                    {{-- HORA --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Hora</label>
                        <input type="time"
                            name="hora"
                            class="form-control @error('hora') is-invalid @enderror"
                            value="{{ old('hora') }}"
                            required>

                        @error('hora')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- OBS --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea name="observacoes"
                            rows="3"
                            class="form-control"></textarea>
                    </div>

                </div>
            </div>
        </div>


        {{-- ================= PAGAMENTO ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-cash-coin me-2"></i>
                Informações de Pagamento
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Valor Total</label>
                        <input type="number" step="0.01"
                            name="valor"
                            class="form-control"
                            placeholder="R$ 0,00"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Valor Pago</label>
                        <input type="number" step="0.01"
                            name="valor_pago"
                            class="form-control"
                            placeholder="R$ 0,00"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Forma</label>
                        <select name="forma_pagamento"
                            class="form-select" required>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="cartao">Cartão</option>
                            <option value="pix">PIX</option>
                            <option value="convenio">Convênio</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status_pagamento"
                            class="form-select" required>
                            <option value="pago">Pago</option>
                            <option value="pendente">Pendente</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>


        {{-- ================= AÇÕES ================= --}}
        <div class="text-end mb-5">
            <a href="{{ route('consultas.index') }}"
                class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>

            <button class="btn btn-primary">
                <i class="bi bi-calendar-check"></i>
                Agendar Consulta
            </button>
        </div>

    </form>


    {{-- ================= MODAL PACIENTES ================= --}}
    <div class="modal fade" id="modalPacientes" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Selecionar Paciente
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="text"
                        id="buscarPacienteModal"
                        class="form-control mb-3"
                        placeholder="Buscar paciente pelo nome">

                    <ul class="list-group" id="listaPacientes">
                        @foreach($pacientes as $p)
                        <li class="list-group-item list-group-item-action"
                            data-id="{{ $p->id }}"
                            data-nome="{{ $p->nome }}">
                            {{ $p->nome }}
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>


    {{-- ================= JS ================= --}}
    <script>
        const buscarInput = document.getElementById('buscarPacienteModal');
        const lista = document.getElementById('listaPacientes');

        buscarInput.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            lista.querySelectorAll('li').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(filtro) ? '' : 'none';
            });
        });

        lista.querySelectorAll('li').forEach(item => {
            item.addEventListener('click', function() {
                paciente_id.value = this.dataset.id;
                paciente_nome.value = this.dataset.nome;
                bootstrap.Modal.getInstance(modalPacientes).hide();
            });
        });

        medicoSelect.addEventListener('change', function() {
            avisoBloqueioMedico.style.display = 'none';
            if (!this.value) return;

            fetch(`/medicos/${this.value}/status-bloqueio`)
                .then(r => r.json())
                .then(d => {
                    if (d.bloqueado) avisoBloqueioMedico.style.display = 'block';
                });
        });
    </script>

    @endsection