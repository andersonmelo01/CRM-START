<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha | CRM Médico</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
            animation: fadeIn .6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(13, 110, 253, .1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-box i {
            font-size: 32px;
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="card p-4" style="width: 420px;">

        <div class="text-center">
            <div class="icon-box">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h4 class="fw-bold">Esqueceu sua senha?</h4>
            <p class="text-muted small">
                Informe seu e-mail cadastrado e enviaremos um link para redefinição.
            </p>
        </div>

        @if (session('status'))
        <div class="alert alert-success text-center">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input
                    type="email"
                    name="email"
                    class="form-control form-control-lg"
                    placeholder="seu@email.com"
                    required>
            </div>

            <button class="btn btn-primary btn-lg w-100">
                <i class="bi bi-envelope"></i>
                Enviar link de recuperação
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Voltar para login
            </a>
        </div>

    </div>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>