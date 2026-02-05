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
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* formato padrão para impressora fiscal
        .cupom {
            width: 100%;
            max-width: 80mm;
            margin: auto;
            padding: 5mm;
        }
        */

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

        .title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .hr {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .highlight {
            font-size: 13px;
            font-weight: bold;
        }

        .status {
            margin: 6px 0;
            padding: 4px 0;
            border: 1px dashed #000;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 9px;
        }

        .print-area {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .print-area button {
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="cupom">

        {{-- EMITENTE --}}
        <div class="center title">
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

            @if($emitente->telefone)
            Tel: {{ $emitente->telefone }}
            @endif
        </div>

        <div class="hr"></div>

        {{-- DADOS DO RECIBO --}}
        <table class="table">
            <tr>
                <td>Recibo Nº</td>
                <td class="right bold">
                    {{ $pagamento->numero_recibo }}
                </td>
            </tr>
            <tr>
                <td>Data</td>
                <td class="right">
                    {{ $pagamento->data_pagamento->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>

        <div class="hr"></div>

        {{-- DADOS DO PACIENTE --}}
        <table class="table">
            <tr>
                <td>Paciente</td>
                <td class="right bold">
                    {{ $pagamento->consulta->paciente->nome }}
                </td>
            </tr>
            <tr>
                <td>Procedimento</td>
                <td class="right">
                    Consulta Médica
                </td>
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
                <td class="right highlight">
                    R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="status">
            PAGAMENTO CONFIRMADO
        </div>

        {{-- RODAPÉ --}}
        <div class="footer">
            Documento sem valor fiscal<br>
            {{ $emitente->mensagem_rodape ?? 'Obrigado pela preferência' }}
        </div>

    </div>
    <!--Impressão-->
    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>