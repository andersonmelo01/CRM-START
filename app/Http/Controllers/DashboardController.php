<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();

        return view('dashboard', [
            'totalPacientes' => Paciente::count(),
            'consultasHoje' => Consulta::whereDate('data', $hoje)->count(),
            'consultasMes' => Consulta::whereMonth('data', now()->month)->count(),
            'atendidas' => Consulta::where('status', 'atendida')->count(),
            'canceladas' => Consulta::where('status', 'cancelada')->count(),
            'agendaHoje' => Consulta::with('paciente')
                ->whereDate('data', $hoje)
                ->orderBy('hora')
                ->get(),
        ]);
    }
}
