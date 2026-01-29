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
        return back()->with('success', 'Horário bloqueado!');
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
        $hoje  = now()->format('Y-m-d');
        $agora = now()->format('H:i');

        $medicos = Medico::all();
        /*$medicos = Medico::whereHas('bloqueios', function ($q) use ($hoje, $agora) {
            $q->where('data', $hoje)
                ->whereTime('hora_inicio', '<=', $agora)
                ->whereTime('hora_fim', '>=', $agora);
        })
            ->with(['bloqueios' => function ($q) use ($hoje, $agora) {
                $q->where('data', $hoje)
                    ->whereTime('hora_inicio', '<=', $agora)
                    ->whereTime('hora_fim', '>=', $agora);
            }])
            ->get();*/

        return view('bloqueios.bloqueados', compact('medicos'));
    }
}
