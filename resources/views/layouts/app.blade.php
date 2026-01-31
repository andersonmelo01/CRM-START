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
    body {
        font-family: 'Poppins', sans-serif;
        background: #f4f6f9;
    }

    /* Navbar */
    .navbar {
        border-bottom: 1px solid #eee;
        backdrop-filter: blur(6px);
    }

    .navbar-brand {
        font-weight: 600;
        letter-spacing: .4px;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        height: 100vh;
        padding: 20px 12px;
        background: #ffffff;
        border-right: 1px solid #eee;
    }

    .list-group-item {
        border: none;
        border-radius: 12px;
        margin-bottom: 6px;
        font-weight: 500;
        color: #495057;
        transition: all .15s ease;
    }

    .list-group-item:hover {
        background: #f1f3f5;
        transform: translateX(3px);
    }

    .list-group-item.active {
        background: #0d6efd;
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, .25);
    }

    /* Conteúdo principal */
    .col-lg-10 {
        padding-top: 25px;
    }

    /* Cards padrão */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
    }

    /* Botões */
    .btn {
        border-radius: 12px;
        font-weight: 500;
        padding: 6px 14px;
    }

    .btn-outline-primary {
        border-width: 2px;
    }

    /* Dropdown */
    .dropdown-menu {
        border-radius: 14px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
    }

    /* Títulos */
    h1,
    h2,
    h3,
    h4,
    h5 {
        font-weight: 600;
        letter-spacing: .3px;
    }

    /* Form */
    .form-control,
    .form-select {
        border-radius: 12px;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
    }

    .form-control:focus {
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
    }

    /* Modal */
    .modal-content {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
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