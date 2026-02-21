@extends('layouts.app')

@section('titulo', 'Agenda')

@section('conteudo')

<!-- Filtro Médico -->
<div class="mb-4" style="max-width: 300px;">
    <label class="fw-semibold">Filtrar por Médico</label>
    <select id="filtro_medico" class="form-select">
        <option value="">Todos</option>
        @foreach(\App\Models\Medico::all() as $m)
        <option value="{{ $m->id }}">{{ $m->nome }}</option>
        @endforeach
    </select>
</div>

<!-- Calendário -->
<!--<div id="calendar" class="card p-3"></div>-->
<div id="calendar" class="card p-3" style="min-height: 600px;"></div>

<!-- Modal Consulta -->
<div class="modal fade" id="consultaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="consulta_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select id="status" class="form-select">
                        <option value="agendada">Agendada</option>
                        <option value="atendida">Atendida</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Observações</label>
                    <textarea id="observacoes_consulta" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <span id="btnProntuario"></span>
                <button class="btn btn-danger" onclick="excluir()">Excluir</button>
                <button class="btn btn-primary" onclick="salvar()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Consulta -->
<div class="modal fade" id="novaConsultaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Paciente</label>
                        <select id="paciente_id" class="form-select">
                            @foreach(\App\Models\Paciente::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Médico</label>
                        <select id="medico_id" class="form-select">
                            @foreach(\App\Models\Medico::all() as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Data</label>
                        <input type="date" id="data" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hora</label>
                        <input type="time" id="hora" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea id="observacoes_nova" class="form-control" rows="4" placeholder="Digite observações da consulta (opcional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="criarConsulta()">Salvar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let medicoSelecionado = '';

        const isMobile = window.innerWidth < 768;

        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: isMobile ? 'timeGridDay' : 'timeGridWeek',
            locale: 'pt-br',
            editable: true,
            selectable: true,
            slotMinTime: '07:00:00',
            slotMaxTime: '19:00:00',
            allDaySlot: false,
            nowIndicator: true,
            height: isMobile ? 'auto' : '80vh',
            expandRows: true,
            stickyHeaderDates: true,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: isMobile ? 'timeGridDay' : 'timeGridDay,timeGridWeek,dayGridMonth'
            },

            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },

            slotDuration: '00:30:00',
            slotLabelInterval: '01:00',

            slotDuration: '00:30:00',
            eventSources: [{
                    url: '/agenda/eventos',
                    method: 'GET',
                    extraParams: function() {
                        return {
                            medico_id: medicoSelecionado
                        };
                    }
                },
                {
                    url: '/bloqueios/eventos',
                    method: 'GET',
                    extraParams: function() {
                        return {
                            medico_id: medicoSelecionado
                        };
                    }
                }
            ],
            dateClick: function(info) {
                const data = info.dateStr.substring(0, 10);
                const hora = info.dateStr.length > 10 ? info.dateStr.substring(11, 16) : '08:00';
                document.getElementById('data').value = data;
                document.getElementById('hora').value = hora;
                new bootstrap.Modal(document.getElementById('novaConsultaModal')).show();
            },
            eventDrop: function(info) {
                const data = info.event.startStr.substring(0, 10);
                const hora = info.event.startStr.substring(11, 16);
                fetch('/agenda/' + info.event.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        data,
                        hora
                    })
                }).then(resp => {
                    if (!resp.ok) {
                        info.revert();
                        alert('Horário já ocupado');
                    }
                });
            },
            eventClick: function(info) {
                fetch('/agenda/consulta/' + info.event.id)
                    .then(r => r.json())
                    .then(c => {
                        document.getElementById('consulta_id').value = c.id;
                        document.getElementById('status').value = c.status;
                        document.getElementById('observacoes_consulta').value = c.observacoes ?? '';
                        let btnArea = document.getElementById('btnProntuario');
                        if (c.status === 'agendada') {
                            if (c.prontuario) {
                                btnArea.innerHTML = `<a href="/prontuarios/${c.id}" class="btn btn-info btn-sm">Ver Prontuário</a>`;
                            } else {
                                btnArea.innerHTML = `<a href="/prontuarios/create/${c.id}" class="btn btn-primary btn-sm">Iniciar Consulta</a>`;
                            }
                        } else {
                            if (c.prontuario) {
                                btnArea.innerHTML = `<a href="/prontuarios/${c.id}" class="btn btn-secondary btn-sm">Ver Prontuário</a>`;
                            } else {
                                btnArea.innerHTML = `<button class="btn btn-secondary btn-sm" disabled>Prontuário indisponível</button>`;
                            }
                        }
                        new bootstrap.Modal(document.getElementById('consultaModal')).show();
                    });
            },
            eventClassNames: function(arg) {
                // Adaptação de cores ao layout 1
                switch (arg.event.extendedProps.status) {
                    case 'agendada':
                        return ['bg-primary', 'text-white', 'rounded', 'p-1'];
                    case 'atendida':
                        return ['bg-success', 'text-white', 'rounded', 'p-1'];
                    case 'cancelada':
                        return ['bg-danger', 'text-white', 'rounded', 'p-1'];
                    default:
                        return ['bg-secondary', 'text-white', 'rounded', 'p-1'];
                }
            }
        });

        calendar.render();

        // Filtro médico
        document.getElementById('filtro_medico').addEventListener('change', function() {
            medicoSelecionado = this.value;
            calendar.refetchEvents();
        });
    });

    function salvar() {
        let id = document.getElementById('consulta_id').value;
        fetch('/agenda/consulta/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: document.getElementById('status').value,
                observacoes: document.getElementById('observacoes_consulta').value
            })
        }).then(() => location.reload());
    }

    function excluir() {
        let id = document.getElementById('consulta_id').value;
        if (!confirm('Deseja excluir esta consulta?')) return;
        fetch('/agenda/consulta/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => location.reload());
    }

    function criarConsulta() {
        const paciente = document.getElementById('paciente_id')?.value;
        const medico = document.getElementById('medico_id')?.value;
        const data = document.getElementById('data')?.value;
        const hora = document.getElementById('hora')?.value;
        const obs = document.getElementById('observacoes_nova')?.value;

        if (!paciente || !medico || !data || !hora) {
            alert('Preencha todos os campos obrigatórios');
            return;
        }

        fetch('/agenda', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    paciente_id: paciente,
                    medico_id: medico,
                    data,
                    hora,
                    observacoes: obs
                })
            }).then(resp => {
                if (!resp.ok) throw new Error('Horário já ocupado');
                return resp.json();
            }).then(() => location.reload())
            .catch(err => alert(err.message));
    }
</script>

<style>
    /* Desktop */
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    /* Melhor legibilidade dos eventos */
    .fc-event {
        font-size: 0.85rem;
        padding: 2px 4px;
    }

    /* Mobile ajustes */
    @media (max-width: 768px) {
        .fc-toolbar-title {
            font-size: 1rem !important;
        }

        .fc-button {
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
        }

        .fc-timegrid-slot {
            height: 40px !important;
        }
    }
</style>

@endsection