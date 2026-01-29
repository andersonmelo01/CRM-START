<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo')</title>

    <title>CRM Médico</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
</style>


<body>
    
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


    <div class="container-fluid">
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

                @yield('conteudo')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>