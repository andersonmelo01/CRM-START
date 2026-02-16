<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo') — CRM Médico</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Fontes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary: #2563eb;
            --secondary: #10b981;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #f8fafc, #eef2f7);
            color: var(--text);
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: .3px;
        }

        /* SIDEBAR */
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            min-height: 100vh;
            padding: 20px 14px;
            transition: all .3s ease;
        }

        .list-group-item {
            border: none;
            border-radius: 14px;
            margin-bottom: 6px;
            padding: 12px 14px;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text);
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
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            color: white;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .35);
        }

        .list-group-item.active i {
            color: white;
        }

        /* CONTEÚDO */
        .content-wrapper {
            padding: 28px 28px 40px;
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 22px;
            background: var(--card);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .06);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }

        /* BOTÕES */
        .btn {
            border-radius: 14px;
            font-weight: 600;
            font-size: .85rem;
            padding: 8px 18px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, .25);
        }

        /* FORM */
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
            box-shadow: 0 0 0 .15rem rgba(37, 99, 235, .15);
        }

        /* MODAL */
        .modal-content {
            border-radius: 24px;
            border: none;
            box-shadow: 0 30px 70px rgba(0, 0, 0, .25);
        }

        /* MOBILE + TABLET */
        @media (max-width: 991px) {
            body {
                font-family: 'Playfair Display', serif;
            }

            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                width: 260px;
                z-index: 1050;
                box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
            }

            .sidebar.show {
                left: 0;
            }

            .content-wrapper {
                padding: 20px 16px 40px;
            }
        }

        /* OVERLAY MOBILE */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            backdrop-filter: blur(2px);
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* TÍTULOS */
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-weight: 600;
            letter-spacing: .3px;
        }

        small,
        .text-muted {
            color: var(--muted) !important;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3">
        <div class="container-fluid">

            <button class="btn btn-light d-lg-none me-2" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-heart-pulse text-primary me-2"></i>
                CRM Médico
            </a>

            <div class="ms-auto">
                @auth
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sair
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

    <!-- OVERLAY MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <nav id="sidebar" class="col-md-3 col-lg-2 sidebar">
                <div class="list-group">

                    <a href="{{ url('/dashboard') }}" class="list-group-item active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>

                    @can('perfil_admin')
                    <a href="{{ route('agenda') }}" class="list-group-item">
                        <i class="bi bi-calendar-week"></i> Agenda
                    </a>
                    <a href="/consultas" class="list-group-item">
                        <i class="bi bi-clipboard2-pulse"></i> Consultas
                    </a>
                    <a href="/pacientes" class="list-group-item">
                        <i class="bi bi-people"></i> Pacientes
                    </a>
                    <a href="/medicos" class="list-group-item">
                        <i class="bi bi-person-badge"></i> Médicos
                    </a>
                    <a href="/pagamentos" class="list-group-item">
                        <i class="bi bi-cash-coin"></i> Pagamentos
                    </a>
                    <a href="{{ route('config') }}" class="list-group-item">
                        <i class="bi bi-gear"></i> Configuração
                    </a>
                    @endcan

                </div>
            </nav>

            <!-- CONTEÚDO -->
            <main class="col-lg-10 ms-sm-auto content-wrapper">
                @yield('conteudo')
            </main>

        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>

</body>

</html>