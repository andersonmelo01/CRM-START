<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Consulta;

class ProntuarioController extends Controller
{
    public function create(Consulta $consulta)
    {
        return view('prontuarios.create', compact('consulta'));
    }

    public function store(Request $request, Consulta $consulta)
    {
        $request->validate([
            'queixa_principal' => 'required',
            'diagnostico' => 'required',
        ]);

        $consulta->prontuario()->create($request->all());

        // 🔥 MUDA O STATUS DA CONSULTA
        $consulta->update([
            'status' => 'atendida'
        ]);

        return redirect()
            ->route('consultas.show', $consulta)
            ->with('success', 'Prontuário salvo com sucesso!');
    }
    public function show($id)
    {
        $consulta = Consulta::with([
            'paciente',
            'medico',
            'prontuario'
        ])->findOrFail($id);

        $prontuario = $consulta->prontuario;

        return view('prontuarios.show', compact('consulta', 'prontuario'));
    }
}
