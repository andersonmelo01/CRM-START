<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Médico - Gestão de Consultas</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(13, 110, 253, 0.9) !important;
        }

        .navbar a.navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: #fff;
            padding: 120px 0 80px;
            position: relative;
            text-align: center;
            overflow: hidden;
            border-radius: 0 0 60px 60px;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 2.5rem;
        }

        .hero p.lead {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .hero .btn {
            transition: all 0.3s ease;
        }

        .hero .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Feature Cards */
        .feature-card {
            border: none;
            border-radius: 18px;
            padding: 2rem 1.5rem;
            background: #fff;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(13, 110, 253, 0.15);
        }

        .icon-box {
            font-size: 38px;
            margin-bottom: 1rem;
        }

        /* CTA */
        .cta {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }

        .cta .btn {
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background: #0a58ca;
            color: #fff;
            padding: 20px 0;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart-pulse-fill"></i> CRM Médico
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">

                @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-person-plus"></i> Cadastrar
                </a>
                @endif
                @endguest

                @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Meu Perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container position-relative">
            <h1 class="mb-3">Sistema CRM para Clínicas e Consultórios</h1>
            <p class="lead mb-4">Gerencie consultas, prontuários, pacientes e financeiro em um único sistema moderno.</p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="{{ route('agendamento.publico') }}" class="btn btn-light btn-lg px-4 shadow-sm"><i class="bi bi-calendar-plus"></i> Agendar Consulta Online</a>
                @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-speedometer2"></i> Ir para o Painel</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-speedometer2"></i> Área da Clínica</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- FUNCIONALIDADES -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Funcionalidades do Sistema</h2>
                <p class="text-muted">Tudo que sua clínica precisa para uma gestão completa</p>
            </div>
            <div class="row g-4">
                @foreach ([
                ['icon'=>'calendar-check','color'=>'primary','title'=>'Agenda de Consultas','desc'=>'Controle completo de agendamentos, bloqueios de horários e conflitos automáticos.'],
                ['icon'=>'journal-medical','color'=>'success','title'=>'Prontuário Eletrônico','desc'=>'Histórico completo do paciente, exames, evoluções e prescrições médicas.'],
                ['icon'=>'cash-coin','color'=>'warning','title'=>'Controle Financeiro','desc'=>'Gestão de pagamentos, faturamento e relatórios financeiros da clínica.'],
                ['icon'=>'envelope-paper','color'=>'danger','title'=>'Lembretes Automáticos','desc'=>'Envio automático de e-mails e WhatsApp lembrando consultas aos pacientes.'],
                ['icon'=>'person-badge','color'=>'info','title'=>'Gestão de Médicos','desc'=>'Controle de especialidades, agendas individuais e disponibilidade.'],
                ['icon'=>'graph-up','color'=>'secondary','title'=>'Relatórios e Indicadores','desc'=>'Métricas de atendimentos, faturamento e desempenho da clínica.']
                ] as $f)
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4 shadow-sm text-center">
                        <div class="icon-box text-{{ $f['color'] }}"><i class="bi bi-{{ $f['icon'] }}"></i></div>
                        <h5 class="fw-bold mt-2">{{ $f['title'] }}</h5>
                        <p class="text-muted">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="container">
            <h3 class="fw-bold mb-3">Pronto para transformar a gestão da sua clínica?</h3>
            @auth
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4 shadow"><i class="bi bi-speedometer2"></i> Acessar Painel</a>
            @else
            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 shadow"><i class="bi bi-box-arrow-in-right"></i> Entrar no Sistema</a>
            @endauth
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-center">
        <div class="container">
            <small>© {{ date('Y') }} CRM Médico - Todos os direitos reservados.</small>
        </div>
    </footer>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>