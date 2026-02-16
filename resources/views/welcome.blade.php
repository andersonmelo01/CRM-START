<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Médico - Gestão de Consultas</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #5B5FEF;
            --secondary: #22C1C3;
            --dark: #0f172a;
            --light: #f1f5f9;
            --card: #ffffff;
            --text: #334155;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            color: var(--text);
        }

        /* NAVBAR MODERNA */
        .navbar {
            backdrop-filter: blur(14px);
            background: rgba(15, 23, 42, 0.75) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: #fff;
        }

        .btn-gradient:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        /* HERO MODERNO */
        .hero {
            position: relative;
            padding: 140px 0 100px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            color: #fff;
            overflow: hidden;
            border-radius: 0 0 80px 80px;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -200px;
            left: -200px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -200px;
            right: -150px;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 2.8rem;
        }

        .hero p {
            font-size: 1.15rem;
            opacity: .9;
        }

        /* FEATURE CARDS MODERNOS */
        .feature-card {
            background: var(--card);
            border-radius: 22px;
            padding: 2.2rem 1.8rem;
            border: 1px solid #eef2f7;
            transition: all .35s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.08);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        /* SEÇÃO FUNCIONALIDADES */
        .section-title h2 {
            font-weight: 700;
            color: var(--dark);
        }

        .section-title p {
            color: #64748b;
        }

        /* CTA MODERNO */
        .cta {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            padding: 90px 0;
            border-radius: 60px 60px 0 0;
            margin-top: 60px;
        }

        .cta h3 {
            font-weight: 700;
        }

        .cta .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 14px 28px;
        }

        /* FOOTER */
        footer {
            background: #020617;
            color: #94a3b8;
            padding: 25px 0;
        }

        /* AVATAR */
        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart-pulse-fill"></i> CRM Médico
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">

                @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-gradient btn-sm rounded-pill px-3">
                    <i class="bi bi-person-plus"></i> Cadastrar
                </a>
                @endif
                @endguest

                @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm rounded-pill dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
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
    <section class="hero text-center">
        <div class="container position-relative">
            <h1 class="mb-3">Sistema CRM Inteligente para Clínicas</h1>
            <p class="lead mb-4">Gerencie consultas, pacientes, prontuários e financeiro com uma experiência moderna e profissional.</p>

            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="{{ route('agendamento.publico') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                    <i class="bi bi-calendar-plus"></i> Agendar Consulta
                </a>

                @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="bi bi-speedometer2"></i> Acessar Painel
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="bi bi-speedometer2"></i> Área da Clínica
                </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- FUNCIONALIDADES -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 section-title">
                <h2>Funcionalidades Principais</h2>
                <p>Ferramentas completas para gestão eficiente da sua clínica</p>
            </div>

            <div class="row g-4">
                @foreach ([
                ['icon'=>'calendar-check','title'=>'Agenda Inteligente','desc'=>'Controle completo de consultas com bloqueios, conflitos automáticos e visual moderno.'],
                ['icon'=>'journal-medical','title'=>'Prontuário Digital','desc'=>'Histórico clínico completo com evoluções, exames e prescrições.'],
                ['icon'=>'cash-coin','title'=>'Financeiro Integrado','desc'=>'Controle de pagamentos, relatórios e faturamento da clínica.'],
                ['icon'=>'envelope-paper','title'=>'Lembretes Automáticos','desc'=>'Envio automático de lembretes por WhatsApp e e-mail para pacientes.'],
                ['icon'=>'person-badge','title'=>'Gestão de Médicos','desc'=>'Controle de agendas individuais, especialidades e disponibilidade.'],
                ['icon'=>'graph-up','title'=>'Relatórios e Indicadores','desc'=>'Métricas detalhadas de atendimentos, faturamento e desempenho.']
                ] as $f)
                <div class="col-md-4">
                    <div class="feature-card text-center h-100">
                        <div class="feature-icon">
                            <i class="bi bi-{{ $f['icon'] }}"></i>
                        </div>
                        <h5 class="fw-bold">{{ $f['title'] }}</h5>
                        <p class="text-muted">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta text-center">
        <div class="container">
            <h3 class="mb-4">Transforme a gestão da sua clínica hoje mesmo</h3>
            @auth
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg shadow">
                <i class="bi bi-speedometer2"></i> Ir para o Painel
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-light btn-lg shadow">
                <i class="bi bi-box-arrow-in-right"></i> Entrar no Sistema
            </a>
            @endauth
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-center">
        <div class="container">
            <small>© {{ date('Y') }} CRM Médico — Sistema de Gestão para Clínicas</small>
        </div>
    </footer>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>