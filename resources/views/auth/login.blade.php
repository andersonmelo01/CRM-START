<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login | CRM Médico</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #20c997);
            height: 100vh;
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
        }

        .login-icon {
            font-size: 3rem;
            color: #0d6efd;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card login-card p-4">

                    <div class="text-center mb-4">
                        <i class="bi bi-heart-pulse login-icon"></i>
                        <h4 class="mt-2 fw-bold">CRM Médico</h4>
                        <p class="text-muted">Acesso ao sistema</p>
                    </div>

                    {{-- ERROS --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input">
                            <label class="form-check-label">Lembrar-me</label>
                        </div>

                        <button class="btn btn-primary w-100 fw-bold">
                            Entrar
                        </button>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('password.request') }}" class="small">
                                Esqueci minha senha
                            </a>

                            <a href="{{ route('register') }}" class="small">
                                Criar conta
                            </a>
                        </div>


                    </form>

                </div>

                <p class="text-center text-white mt-3 small">
                    © {{ date('Y') }} CRM Médico
                </p>

            </div>
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>