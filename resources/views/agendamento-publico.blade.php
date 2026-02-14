<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendamento Online</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <style>
        body {
            background: linear-gradient(180deg, #f4f6f9 0%, #eef2f7 100%);
        }

        .hero {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 50px 0 70px 0;
        }

        .card-agendamento {
            border-radius: 16px;
            margin-top: -50px;
        }

        .hora-btn.active {
            background: #0d6efd;
            color: #fff;
        }
    </style>
</head>

<body>

    <section class="hero text-center">
        <div class="container">
            <h2><i class="bi bi-calendar2-check"></i> Agendamento Online</h2>
            <p>Escolha médico, data e horário</p>
        </div>
    </section>

    <div class="container pb-5" style="max-width:950px;">
        <div class="card card-agendamento shadow-lg p-4">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('agendamento.publico.store') }}">
                @csrf

                <div class="row g-4">

                    <div class="col-md-6">
                        <label>CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" required>
                        <div id="cpf-feedback" class="small mt-1"></div>
                    </div>

                    <div class="col-md-6">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Telefone</label>
                        <input type="text" name="telefone" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Data Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Médico</label>
                        <select name="medico_id" id="medico_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">
                                Dr(a). {{ $medico->nome }} - {{ $medico->especialidade }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Data</label>
                        <input type="date" name="data" id="data" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Horários</label>
                        <div id="horarios" class="d-flex flex-wrap gap-2"></div>
                        <input type="hidden" name="hora" id="hora">
                    </div>

                    <div class="col-12">
                        <label>Observações</label>
                        <textarea name="observacoes" class="form-control"></textarea>
                    </div>

                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Confirmar Agendamento
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.getElementById('cpf').addEventListener('blur', verificarCPF);

        function somenteNumeros(valor) {
            return valor.replace(/\D/g, '');
        }

        function verificarCPF() {
            let cpfInput = document.getElementById('cpf');
            let cpf = somenteNumeros(cpfInput.value);
            let feedback = document.getElementById('cpf-feedback');

            if (cpf.length < 11) return;

            fetch(`/agendamento/verificar-cpf?cpf=${cpf}`)
                .then(res => res.json())
                .then(data => {

                    if (data.existe) {
                        feedback.innerHTML = '<span class="text-success fw-semibold">Paciente encontrado ✔</span>';

                        document.querySelector('[name="nome"]').value = data.nome ?? '';
                        document.querySelector('[name="telefone"]').value = data.telefone ?? '';
                        document.querySelector('[name="email"]').value = data.email ?? '';
                        document.querySelector('[name="data_nascimento"]').value = data.data_nascimento ?? '';

                        desbloquearFormulario();

                    } else {
                        feedback.innerHTML = `
                    <span class="text-danger fw-semibold">
                        CPF não cadastrado. Faça o pré-cadastro.
                    </span>
                    <br>
                    <a href="/pre-cadastro" class="btn btn-sm btn-outline-danger mt-2">
                        Fazer Pré-Cadastro
                    </a>
                `;

                        limparCampos();
                        bloquearFormulario();
                    }
                });
        }

        function bloquearFormulario() {
            document.querySelector('button[type="submit"]').disabled = true;
        }

        function desbloquearFormulario() {
            document.querySelector('button[type="submit"]').disabled = false;
        }

        function limparCampos() {
            document.querySelector('[name="nome"]').value = '';
            document.querySelector('[name="telefone"]').value = '';
            document.querySelector('[name="email"]').value = '';
            document.querySelector('[name="data_nascimento"]').value = '';
        }

        // Horários
        document.getElementById('data').addEventListener('change', carregarHorarios);
        document.getElementById('medico_id').addEventListener('change', carregarHorarios);

        function carregarHorarios() {
            let medico = document.getElementById('medico_id').value;
            let data = document.getElementById('data').value;
            if (!medico || !data) return;

            fetch(`/agendamento/horarios?medico_id=${medico}&data=${data}`)
                .then(res => res.json())
                .then(horarios => {
                    let div = document.getElementById('horarios');
                    div.innerHTML = '';

                    horarios.forEach(h => {
                        let btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-outline-primary';
                        btn.innerText = h;

                        btn.onclick = () => {
                            document.getElementById('hora').value = h;
                            document.querySelectorAll('#horarios button').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                        };

                        div.appendChild(btn);
                    });
                });
        }
    </script>

</body>

</html>