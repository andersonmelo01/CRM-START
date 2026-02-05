<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo') — CRM Médico</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Fonte moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<style>
    :root {
        --primary: #1d4ed8;
        --secondary: #10b981;
        --bg: #f8fafc;
        --card: #ffffff;
        --text: #1f2937;
        --muted: #6b7280;
    }

    body {
        font-family: 'Nunito', 'Poppins', sans-serif;
        background: var(--bg);
        color: var(--text);
    }

    /* NAVBAR */
    .navbar {
        background: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid #e5e7eb;
        backdrop-filter: blur(10px);
    }

    .navbar-brand {
        font-weight: 600;
        font-size: 1.2rem;
        letter-spacing: .4px;
    }

    /* SIDEBAR */
    .sidebar {
        background: linear-gradient(180deg, #ffffff, #f9fafb);
        border-right: 1px solid #e5e7eb;
        padding: 22px 14px;
    }

    .list-group-item {
        border: none;
        border-radius: 14px;
        margin-bottom: 8px;
        padding: 12px 16px;
        font-weight: 500;
        font-size: 0.95rem;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all .2s ease;
    }

    .list-group-item i {
        font-size: 1.1rem;
        color: var(--primary);
    }

    .list-group-item:hover {
        background: #eef2ff;
        transform: translateX(4px);
    }

    .list-group-item.active {
        background: linear-gradient(135deg, var(--primary), #2563eb);
        color: white;
        box-shadow: 0 8px 22px rgba(29, 78, 216, .35);
    }

    .list-group-item.active i {
        color: white;
    }

    /* CONTEÚDO */
    .col-lg-10 {
        padding-top: 28px;
        padding-bottom: 30px;
    }

    /* CARDS */
    .card {
        border: none;
        border-radius: 20px;
        background: var(--card);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .06);
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* BOTÕES */
    .btn {
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 18px;
        transition: all .2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #2563eb);
        border: none;
    }

    .btn-primary:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    /* FORMULÁRIOS */
    .form-control,
    .form-select {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        font-size: .9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(29, 78, 216, .15);
    }

    /* MODAL */
    .modal-content {
        border-radius: 22px;
        border: none;
        box-shadow: 0 25px 60px rgba(0, 0, 0, .25);
    }

    /* DROPDOWN */
    .dropdown-menu {
        border-radius: 18px;
        border: none;
        box-shadow: 0 18px 40px rgba(0, 0, 0, .15);
    }

    /* TÍTULOS */
    h1,
    h2,
    h3,
    h4,
    h5 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        letter-spacing: .3px;
    }

    small,
    .text-muted {
        color: var(--muted) !important;
    }
   
</style>


<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-heart-pulse me-2 text-primary"></i>
                CRM Médico
            </a>

            <div class="ms-auto">

                @auth
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
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

    <!-- LAYOUT -->
    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <div class="list-group">

                    <a href="{{ url('/dashboard') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>

                    @can('perfil_admin')
                    <a href="{{ route('agenda') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-calendar-week me-2"></i>Agenda
                    </a>

                    <a href="/consultas" class="list-group-item list-group-item-action">
                        <i class="bi bi-clipboard2-pulse me-2"></i>Consultas
                    </a>

                    <a href="/pacientes" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2"></i>Pacientes
                    </a>

                    <a href="/medicos" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-badge me-2"></i>Médicos
                    </a>

                    <a href="/pagamentos" class="list-group-item list-group-item-action">
                        <i class="bi bi-cash-coin me-2"></i>Pagamentos
                    </a>

                    <a href="{{ route('config') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i>Configuração
                    </a>
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

            <!-- CONTEÚDO -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('conteudo')
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>