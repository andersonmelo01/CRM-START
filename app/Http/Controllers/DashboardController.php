<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Pagamento;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $user = Auth::user();

        $consultaQuery = Consulta::query();

        // 👨‍⚕️ médico vê só dele
        if ($user && $user->can('perfil_medico') && $user->medico) {
            $consultaQuery->where('medico_id', $user->medico->id);
        }

        // 💰 financeiro (filtrado por médico se necessário)
        $pagamentoQuery = Pagamento::query();

        if ($user && $user->can('perfil_medico') && $user->medico) {
            $pagamentoQuery->whereHas('consulta', function ($q) use ($user) {
                $q->where('medico_id', $user->medico->id);
            });
        }

        return view('dashboard', [

            'totalPacientes' => Paciente::count(),

            'consultasHoje' => (clone $consultaQuery)
                ->whereDate('data', $hoje)
                ->count(),

            'consultasMes' => (clone $consultaQuery)
                ->whereMonth('data', now()->month)
                ->count(),

            'atendidas' => (clone $consultaQuery)
                ->where('status', 'atendida')
                ->count(),

            'canceladas' => (clone $consultaQuery)
                ->where('status', 'cancelada')
                ->count(),

            'agendaHoje' => (clone $consultaQuery)
                ->with('paciente')
                ->whereDate('data', $hoje)
                ->orderBy('hora')
                ->get(),

            // ✅ variáveis que sua view usa
            // Total recebido hoje
            'totalRecebido' => (clone $pagamentoQuery)
                ->where('status', 'pago')
                ->whereDate('data_pagamento', $hoje)
                ->sum('valor'),

            // Total pendente hoje
            'totalPendente' => (clone $pagamentoQuery)
                ->where('status', 'pendente')
                ->whereDate('data_pagamento', $hoje)
                ->sum('valor'),

            // Total geral do dia (pago + pendente, por exemplo)
            'totalHoje' => (clone $pagamentoQuery)
                ->whereDate('data_pagamento', $hoje)
                ->sum('valor'),
        ]);
    }
}
