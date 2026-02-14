<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Pré-cadastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 16px;
        }

        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
            border-radius: 16px 16px 0 0 !important;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card shadow">
                    <div class="card-header text-center py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus-fill"></i> Pré-cadastro do Paciente
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('pre-cadastro.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person"></i> Nome completo
                                </label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text"></i> CPF
                                </label>
                                <input type="text" name="cpf" class="form-control" placeholder="000.000.000-00" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event"></i> Data de Nascimento
                                </label>
                                <input type="date" name="data_nascimento" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </label>
                                <input type="text" name="telefone" class="form-control" placeholder="(22) 99999-9999" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope"></i> E-mail
                                </label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt"></i> Endereço
                                </label>
                                <textarea name="endereco" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Enviar pré-cadastro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <p class="text-center text-muted mt-3">
                    Após o cadastro, você poderá realizar o agendamento online.
                </p>

            </div>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>