<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pedido de Exame</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding: 40px;
            font-family: Arial, sans-serif;
        }

        .header {
            border-bottom: 2px solid #000;
            margin-bottom: 30px;
        }

        .titulo {
            font-size: 22px;
            font-weight: bold;
        }

        .campo {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .box {
            border: 1px solid #000;
            padding: 15px;
            min-height: 80px;
        }

        .assinatura {
            margin-top: 80px;
            text-align: center;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <div class="titulo">PEDIDO DE EXAME</div>
            <div>Consulta #{{ $exame->consulta->id }}</div>
            <div>Prontuário #{{ $exame->prontuario->id }}</div>
        </div>
        <div class="text-end">
            <strong>Data:</strong> {{ date('d/m/Y') }}
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 campo">
            <div class="label">Paciente</div>
            <div class="box">{{ $exame->consulta->paciente->nome }}</div>
        </div>

        <div class="col-md-6 campo">
            <div class="label">Médico</div>
            <div class="box">
                {{ $exame->consulta->medico->nome }} <br>
                CRM: {{ $exame->consulta->medico->crm ?? '—' }}
            </div>
        </div>
    </div>

    <div class="campo">
        <div class="label">Exame Solicitado</div>
        <div class="box">
            <strong>{{ $exame->tipo_exame }}</strong>
        </div>
    </div>

    <div class="campo">
        <div class="label">Descrição / Observações</div>
        <div class="box">
            {{ $exame->descricao ?? '—' }}
        </div>
    </div>

    <div class="campo">
        <div class="label">Data da Solicitação</div>
        <div class="box">
            {{ \Carbon\Carbon::parse($exame->data_solicitacao)->format('d/m/Y') }}
        </div>
    </div>

    <div class="assinatura">
        <p>________________________________________</p>
        <strong>{{ $exame->consulta->medico->nome }}</strong><br>
        Médico Responsável
    </div>

</body>

</html>