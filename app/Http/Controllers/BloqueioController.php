<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloqueio;
use App\Models\Medico;


class BloqueioController extends Controller
{
    public function index()
    {
        $medicos = Medico::all();
        return view('agenda.bloqueios', compact('medicos'));
    }

    public function store(Request $request)
    {
        Bloqueio::create($request->all());
        return redirect()->route('bloqueios.bloqueados')->with('success', 'Horário bloqueado!');
    }

    public function destroy($id)
    {
        $bloqueio = Bloqueio::findOrFail($id);
        $bloqueio->delete();

        return redirect()->route('bloqueios.bloqueados')
            ->with('success', 'Horário desbloqueado com sucesso!');
    }

    public function eventos()
    {
        return Bloqueio::with('medico')->get()->map(function ($b) {
            return [
                'title' => $b->motivo ?? 'Bloqueado',
                'start' => $b->data . 'T' . ($b->hora_inicio ?? '00:00'),
                'end'   => $b->data . 'T' . ($b->hora_fim ?? '23:59'),
                'color' => '#6c757d',
                'editable' => false,
                'display' => 'background' // 🔥 bloqueio visual
            ];
        });
    }
    public function medicosBloqueados()
    {
        // Todos os bloqueios, incluindo bloqueios gerais (sem médico)
        $bloqueios = Bloqueio::with('medico')
            ->orderBy('data', 'desc')
            ->get();

        // Opcional: todos os médicos (se precisar na view)
        $medicos = Medico::all();

        return view('bloqueios.bloqueados', compact('bloqueios', 'medicos'));
    }
    public function bloqueados()
    {
        // Todos os médicos
        $medicos = Medico::with('bloqueios')->get();

        // Todos os bloqueios (ou filtrar por data, futuro, etc.)
        $bloqueios = Bloqueio::with('medico')->orderBy('data', 'desc')->get();

        return view('bloqueios.bloqueados', compact('medicos', 'bloqueios'));
    }
}
