<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Medico;

class RelatorioFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $inicio = $request->data_inicio ?? now()->startOfMonth()->format('Y-m-d');
        $fim    = $request->data_fim ?? now()->format('Y-m-d');
        $medico_id = $request->medico_id;
        $especialidade = $request->especialidade;

        // Médicos e especialidades para os filtros
        $medicos = Medico::all();
        $especialidades = Medico::select('especialidade')->distinct()->pluck('especialidade');

        // Query de pagamentos com relacionamentos
        $query = Pagamento::with(['consulta', 'consulta.paciente', 'consulta.medico'])
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59']);

        // Filtro por médico
        if ($medico_id) {
            $query->whereHas('consulta', fn($q) => $q->where('medico_id', $medico_id));
        }

        // Filtro por especialidade
        if ($especialidade) {
            $query->whereHas('consulta.medico', fn($q) => $q->where('especialidade', $especialidade));
        }

        $pagamentos = $query->orderBy('created_at', 'desc')->get();

        // Totais
        $totalFaturado = $pagamentos->sum('valor');
        $totalRecebido = $pagamentos->sum('valor_pago');
        $totalPendente = $totalFaturado - $totalRecebido;

        return view('relatorios.financeiro', compact(
            'pagamentos',
            'totalFaturado',
            'totalRecebido',
            'totalPendente',
            'inicio',
            'fim',
            'medicos',
            'especialidades',
            'medico_id',
            'especialidade'
        ));
    }
}
