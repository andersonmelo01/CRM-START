<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pré Consulta</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f6f8; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:25px; border-radius:8px;">

        <h2 style="color:#0d6efd;">📅 Pré-Consulta Agendada</h2>

        <p>Olá <strong>{{ $paciente->nome }}</strong>,</p>

        <p>Sua consulta Esta aguardando confirmação, em breve entraremos em contato.</p>

        <hr>

        <p><strong>Médico:</strong> {{ $medico->nome }}</p>
        <p><strong>Data e Hora:</strong> {{ $dataHora }}</p>

        <hr>

        <p>Obrigado pela preferencia.</p>

        <p style="margin-top:30px;">
            Atenciosamente,<br>
            <strong>Equipe da Clínica/Consultório</strong>
        </p>
    </div>
</body>

</html>