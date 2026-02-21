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
                        <select name="data" id="dataSelect" class="form-select" required>
                            <option value="">Selecione o médico primeiro</option>
                        </select>
                    </div>

                    {{-- HORA --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Hora</label>
                        <select name="hora" id="horaSelect" class="form-select @error('hora') is-invalid @enderror" required>
                            <option value="">Selecione a hora</option>
                        </select>

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
        const medicoSelect = document.getElementById('medicoSelect');
        const dataSelect = document.getElementById('dataSelect');
        const horaSelect = document.getElementById('horaSelect');

        let agendaCache = {}; // guarda horários livres do backend

        // ==================== Mudança de médico ====================
        medicoSelect.addEventListener('change', function() {
            const medicoId = this.value;

            dataSelect.innerHTML = '<option value="">Carregando datas...</option>';
            horaSelect.innerHTML = '<option value="">Selecione a data</option>';
            horaSelect.classList.remove('is-invalid');
            removeHoraFeedback();

            if (!medicoId) return;

            fetch(`/agenda-medicos/disponivel/${medicoId}`)
                .then(res => res.json())
                .then(agenda => {
                    agendaCache = agenda; // salva no cache
                    dataSelect.innerHTML = '<option value="">Selecione a data</option>';

                    // adiciona datas
                    Object.keys(agenda).forEach(data => {
                        const [ano, mes, dia] = data.split('-');
                        const opt = document.createElement('option');
                        opt.value = data;
                        opt.textContent = `${dia}/${mes}/${ano}`;
                        dataSelect.appendChild(opt);
                    });
                });
        });

        // ==================== Mudança de data ====================
        dataSelect.addEventListener('change', function() {
            const dataSelecionada = this.value;
            horaSelect.innerHTML = '<option value="">Selecione a hora</option>';
            horaSelect.classList.remove('is-invalid');
            removeHoraFeedback();

            if (!dataSelecionada || !agendaCache[dataSelecionada]) return;

            // adiciona horários livres
            agendaCache[dataSelecionada].forEach(h => {
                const opt = document.createElement('option');
                opt.value = h.hora;
                opt.textContent = h.hora;
                horaSelect.appendChild(opt);
            });
        });

        // ==================== Validação em tempo real do horário ====================
        // validação em tempo real do horário
        horaSelect.addEventListener('change', function() {
            const medicoId = medicoSelect.value;
            const data = dataSelect.value;
            const hora = horaSelect.value;

            // remove mensagem antiga
            removeHoraFeedback();

            if (!medicoId || !data || !hora) return;

            fetch('{{ route("consultas.verificarHora") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        medico_id: medicoId,
                        data: data,
                        hora: hora
                    })
                })
                .then(res => res.json())
                .then(res => {
                    const div = document.createElement('div');
                    div.classList.add('invalid-feedback');
                    div.textContent = res.mensagem;
                    horaSelect.insertAdjacentElement('afterend', div);

                    if (!res.disponivel) {
                        horaSelect.classList.add('is-invalid');
                    } else {
                        horaSelect.classList.remove('is-invalid');
                    }
                })
                .catch(err => console.error(err));
        });

        // função auxiliar para remover feedback antigo
        function removeHoraFeedback() {
            const feedback = horaSelect.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.remove();
            }
        }
    </script>

    {{-- ========== Modal ========= --}}
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