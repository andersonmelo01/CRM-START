<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recibo</title>

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: auto;
            margin: 0;
        }

        body {
            font-family: "Courier New", monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
        }

        .cupom {
            width: 100%;
            max-width: 80mm;
            /* 58mm ou 80mm */
            margin: auto;
            padding: 4mm;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 9px;
        }

        .hr {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .footer {
            margin-top: 6px;
            text-align: center;
            font-size: 9px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .print-area {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="cupom">

        {{-- EMITENTE --}}
        <div class="center bold">
            {{ $emitente->nome }}
        </div>

        <div class="center small">
            {{ $emitente->documento }}<br>

            @if($emitente->endereco)
            {{ $emitente->endereco }}<br>
            @endif

            @if($emitente->cidade || $emitente->uf)
            {{ $emitente->cidade }}{{ $emitente->cidade && $emitente->uf ? ' - ' : '' }}{{ $emitente->uf }}<br>
            @endif

            @if($emitente->telefone ?? ' não informado')
            Tel: {{ $emitente->telefone }}
            @endif
        </div>

        <div class="hr"></div>

        {{-- DADOS DO RECIBO --}}
        <table class="table">
            <tr>
                <td>Recibo Nº</td>
                <td class="right bold">{{ $pagamento->numero_recibo }}</td>
            </tr>
            <tr>
                <td>Data</td>
                <td class="right">
                    {{ $pagamento->data_pagamento->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>

        <div class="hr"></div>

        {{-- PACIENTE --}}
        <table class="table">
            <tr>
                <td>Paciente</td>
                <td class="right">{{ $pagamento->consulta->paciente->nome }}</td>
            </tr>
            <tr>
                <td>Procedimento</td>
                <td class="right">Consulta</td>
            </tr>
        </table>

        <div class="hr"></div>

        {{-- VALORES --}}
        <table class="table">
            <tr>
                <td>Valor Total</td>
                <td class="right">
                    R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Valor Pago</td>
                <td class="right">
                    R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="hr"></div>

        <div class="center bold">
            PAGAMENTO CONFIRMADO
        </div>

        <div class="hr"></div>

        {{-- RODAPÉ --}}
        <div class="footer">
            Documento sem valor fiscal<br>
            {{ $emitente->mensagem_rodape ?? 'Obrigado pela preferência' }}
        </div>

    </div>

    {{-- BOTÃO IMPRIMIR --}}
    <div class="print-area no-print">
        <button onclick="window.print()">
            Imprimir
        </button>
    </div>

</body>

</html>