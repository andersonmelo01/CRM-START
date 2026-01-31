<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PedidoExame;
use App\Models\Consulta;

class PedidoExameController extends Controller
{
    public function store(Request $request, $consultaId)
    {
        $consulta = Consulta::findOrFail($consultaId);

        PedidoExame::create([
            'consulta_id' => $consulta->id,
            'prontuario_id' => $consulta->prontuario?->id,
            'tipo_exame' => $request->tipo_exame,
            'descricao' => $request->descricao,
            'data_solicitacao' => now(),
        ]);

        return back()->with('ok', 'Exame solicitado');
    }

    public function updateResultado(Request $r, PedidoExame $exame)
    {
        $exame->update([
            'resultado' => $r->resultado,
            'status' => 'entregue'
        ]);

        return back();
    }
    public function porConsulta($consultaId)
    {
        $consulta = Consulta::with([
            'paciente',
            'medico',
            'exames'
        ])->findOrFail($consultaId);

        return view('exames.index', compact('consulta'));
    }
}
