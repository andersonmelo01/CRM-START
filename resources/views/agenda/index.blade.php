<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agenda</title>

    <title>CRM Médico</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<!-- Adicione CSS para estilizar a classe .sidebar se necessário -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f4f6f9;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        height: 100vh;
        padding-top: 1rem;
        background: #ffffff;
        border-right: 1px solid #eee;
    }

    .list-group-item {
        border: none;
        border-radius: 12px;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .list-group-item.active {
        background: #0d6efd;
    }

    /* Calendar Card */
    #calendar {
        max-width: 1200px;
        margin: 30px auto;
        background: #ffffff;
        padding: 20px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
    }

    /* Toolbar */
    .fc-toolbar {
        margin-bottom: 15px;
    }

    .fc-toolbar-title {
        font-weight: 600;
        font-size: 1.5rem;
        letter-spacing: 0.5px;
    }

    .fc-button {
        border-radius: 12px !important;
        border: none !important;
        background: #0d6efd !important;
        padding: 6px 14px !important;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        transition: all .15s ease;
    }

    .fc-button:hover {
        background: #0b5ed7 !important;
        transform: translateY(-1px);
    }

    /* Eventos */
    .fc-event {
        border-radius: 10px !important;
        border: none !important;
        padding: 3px 6px !important;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        transition: all .15s ease;
    }

    .fc-event:hover {
        transform: scale(1.03);
        filter: brightness(1.05);
    }

    /* Grade mais suave */
    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: #eef1f5;
    }

    .fc-timegrid-slot {
        height: 42px !important;
    }

    /* Linha hora atual */
    .fc-timegrid-now-indicator-line {
        border-color: #dc3545;
        border-width: 2px;
    }

    /* Modais */
    .modal-content {
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        let medicoSelecionado = '';

        const calendar = new FullCalendar.Calendar(
            document.getElementById('calendar'), {

                initialView: 'timeGridWeek',
                locale: 'pt-br',
                editable: true,
                selectable: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                allDaySlot: false,
                nowIndicator: true,
                height: 'auto',

                loading: function(isLoading) {
                    document.body.style.cursor = isLoading ? 'wait' : 'default';
                },

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                slotDuration: '00:30:00',

                eventSources: [{
                        url: '/agenda/eventos',
                        method: 'GET',
                        extraParams: function() {
                            return {
                                medico_id: medicoSelecionado
                            }
                        }
                    },
                    {
                        url: '/bloqueios/eventos',
                        method: 'GET',
                        extraParams: function() {
                            return {
                                medico_id: medicoSelecionado
                            }
                        }
                    }
                ],

                dateClick: function(info) {

                    const data = info.dateStr.substring(0, 10);
                    const hora = info.dateStr.length > 10 ?
                        info.dateStr.substring(11, 16) :
                        '08:00';

                    document.getElementById('data').value = data;
                    document.getElementById('hora').value = hora;

                    new bootstrap.Modal(
                        document.getElementById('novaConsultaModal')
                    ).show();
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
                        })
                        .then(resp => {
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

                            // ✅ REGRA DE LIBERAÇÃO
                            if (c.status === 'agendada') {

                                if (c.prontuario) {
                                    btnArea.innerHTML =
                                        `<a href="/prontuarios/${c.id}" class="btn btn-info btn-sm">
                            Ver Prontuário
                         </a>`;
                                } else {
                                    btnArea.innerHTML =
                                        `<a href="/prontuarios/create/${c.id}" class="btn btn-primary btn-sm">
                            Iniciar Consulta
                         </a>`;
                                }

                            } else {

                                // ❌ NÃO AGENDADA → só visualizar se existir
                                if (c.prontuario) {
                                    btnArea.innerHTML =
                                        `<a href="/prontuarios/${c.id}" class="btn btn-secondary btn-sm">
                            Ver Prontuário
                         </a>`;
                                } else {
                                    btnArea.innerHTML =
                                        `<button class="btn btn-secondary btn-sm" disabled>
                            Prontuário indisponível
                         </button>`;
                                }
                            }

                            new bootstrap.Modal(
                                document.getElementById('consultaModal')
                            ).show();
                        });
                }

            });

        calendar.render();

        // ✅ FILTRO MÉDICO — recarregar eventos
        document.getElementById('filtro_medico')
            .addEventListener('change', function() {
                medicoSelecionado = this.value;
                calendar.refetchEvents();
            });

    });
</script>


<script>
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
            })
            .then(() => location.reload());
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
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    paciente_id: paciente,
                    medico_id: medico,
                    data: data,
                    hora: hora,
                    observacoes: obs
                })
            })
            .then(resp => {
                if (!resp.ok) {
                    throw new Error('Horário já ocupado');
                }
                return resp.json();
            })
            .then(() => location.reload())
            .catch(err => alert(err.message));
    }
</script>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-bottom">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                    CRM Médico
                </a>

                <div class="ms-auto">

                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth

                    @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        Entrar
                    </a>
                    @endguest

                </div>
            </div>
        </nav>
        <div class="row">
            <!-- Menu Lateral (Sidebar) -->
            <div class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
                <div class="list-group">
                    <a href="{{ url('/dashboard') }}" class="list-group-item list-group-item-action active">Dashboard</a>
                    @can('perfil_admin')
                    <a href="{{ route('agenda') }}" class="list-group-item list-group-item-action">Agenda</a>
                    <a href="/consultas" class="list-group-item list-group-item-action">Consultas</a>
                    <a href="/pacientes" class="list-group-item list-group-item-action">Pacientes</a>
                    <a href="/medicos" class="list-group-item list-group-item-action">Médicos</a>
                    <a href="/pagamentos" class="list-group-item list-group-item-action">Pagamentos</a>
                    <a href="{{ route('config') }}" class=" list-group-item list-group-item-action">Configuração</a>
                    @endcan
                    @can('perfil_recepcao')
                    <a href="/pacientes" class="list-group-item list-group-item-action">Pacientes</a>
                    <a href="/medicos" class="list-group-item list-group-item-action">Médicos</a>
                    <a href="/pagamentos" class="list-group-item list-group-item-action">Pagamentos</a>
                    @endcan
                    @can('perfil_medico')
                    <a href="{{ route('agenda') }}" class="list-group-item list-group-item-action">Agenda</a>
                    <a href="/consultas" class="list-group-item list-group-item-action">Consultas</a>
                    @endcan
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!--Filtro de Médico-->
                <div class="mb-3">
                    <label><strong>Filtrar por Médico</strong></label>
                    <select id="filtro_medico" class="form-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Medico::all() as $m)
                        <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <!--Calendar-->
                <div id="calendar"></div>
                <!-- Modal -->
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

                                <!-- botão prontuário dinâmico -->
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
                                        <textarea id="observacoes_nova"
                                            class="form-control"
                                            rows="4"
                                            placeholder="Digite observações da consulta (opcional)"></textarea>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-success"
                                    onclick="criarConsulta()">
                                    Salvar
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>