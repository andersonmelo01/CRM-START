<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criar Conta</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #20c997, #0d6efd);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
            animation: fadeIn .5s ease;
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
            background: rgba(32, 201, 151, .15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .icon-box i {
            font-size: 32px;
            color: #20c997;
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">

            <div class="card p-4 p-md-5">

                <div class="text-center mb-4">
                    <div class="icon-box mb-3">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h4 class="fw-bold">Criar Conta</h4>
                    <p class="text-muted small">
                        Preencha os dados para acessar o sistema
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmar Senha</label>
                        <input type="password" name="password_confirmation"
                            class="form-control form-control-lg" required>
                    </div>

                    <button class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-circle"></i> Criar Conta
                    </button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted small">Já possui conta?</span><br>
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                        Entrar no sistema
                    </a>
                </div>

            </div>

        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>