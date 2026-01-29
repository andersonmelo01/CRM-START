<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agenda/title>

        <title>CRM Médico</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<!-- Adicione CSS para estilizar a classe .sidebar se necessário -->
<style>
    .sidebar {
        position: fixed;
        /* Ou sticky */
        height: 100vh;
        padding-top: 1rem;
    }

    #calendar {
        max-width: 1200px;
        margin: 30px auto;
        background: #fff;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, .05);
    }

    .fc-toolbar-title {
        font-weight: 600;
        font-size: 1.4rem;
    }

    .fc-button {
        border-radius: 6px !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const calendar = new FullCalendar.Calendar(
            document.getElementById('calendar'), {

                initialView: 'timeGridWeek',
                locale: 'pt-br',
                editable: true,
                selectable: true, // ✅ obrigatório
                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                allDaySlot: false,
                nowIndicator: true,
                height: 'auto',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                slotDuration: '00:30:00',

                //events: '/agenda/eventos',

                eventSources: [{
                        url: '/agenda/eventos',
                        method: 'GET'
                    },
                    {
                        url: '/bloqueios/eventos',
                        method: 'GET'
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
                            document.getElementById('observacoes').value = c.observacoes ?? '';

                            new bootstrap.Modal(
                                document.getElementById('consultaModal')
                            ).show();
                        });
                }
            });

        calendar.render();
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
                    observacoes: document.getElementById('observacoes').value
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
        const obs = document.getElementById('observacoes')?.value;

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
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
                <div id="calendar"></div>
                <!-- Modal -->
                @foreach($consultas as $c)
                <div class="modal fade" id="consultaModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Consulta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="consulta_id">

                                <label>Status</label>
                                <select id="status" class="form-control">
                                    <option value="agendada">Agendada</option>
                                    <option value="atendida">Atendida</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>

                                <label class="mt-2">Observações</label>
                                <textarea id="observacoes" class="form-control"></textarea>
                            </div>

                            <div class="modal-footer">
                                @if($c->prontuario)
                                <a href="{{ route('prontuarios.show', $c) }}"
                                    class="btn btn-info btn-sm">
                                    Ver Prontuário
                                </a>
                                @else
                                <a href="{{ route('prontuarios.create', $c) }}"
                                    class="btn btn-primary btn-sm">
                                    Iniciar Consulta
                                </a>
                                @endif
                                <button class="btn btn-danger" onclick="excluir()">Excluir</button>
                                <button class="btn btn-primary" onclick="salvar()">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Modal Nova Consulta -->
                <div class="modal fade" id="novaConsultaModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nova Consulta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <label>Paciente</label>
                                <select id="paciente_id" class="form-control">
                                    @foreach(\App\Models\Paciente::all() as $p)
                                    <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>

                                <label class="mt-2">Médico</label>
                                <select id="medico_id" class="form-control">
                                    @foreach(\App\Models\Medico::all() as $m)
                                    <option value="{{ $m->id }}">{{ $m->nome }}</option>
                                    @endforeach
                                </select>

                                <label class="mt-2">Data</label>
                                <input type="date" id="data" class="form-control">

                                <label class="mt-2">Hora</label>
                                <input type="time" id="hora" class="form-control">

                                <label class="mt-2">Observações</label>
                                <textarea id="observacoes" class="form-control"></textarea>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>