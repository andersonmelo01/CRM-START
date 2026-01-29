<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;


class RelatorioFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $inicio = $request->data_inicio;
        $fim = $request->data_fim;

        $query = Pagamento::with('consulta.paciente');

        if ($inicio && $fim) {
            $query->whereBetween('created_at', [
                $inicio . ' 00:00:00',
                $fim . ' 23:59:59'
            ]);
        }

        $pagamentos = $query->get();

        $totalFaturado = $pagamentos->sum('valor');
        $totalRecebido = $pagamentos->sum('valor_pago');
        $totalPendente = $totalFaturado - $totalRecebido;

        return view('relatorios.financeiro', compact(
            'pagamentos',
            'totalFaturado',
            'totalRecebido',
            'totalPendente',
            'inicio',
            'fim'
        ));
    }
}
