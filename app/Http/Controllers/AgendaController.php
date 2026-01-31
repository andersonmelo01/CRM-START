<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;

class AgendaController extends Controller
{
    public function index()
    {
        $medicos = Medico::orderBy('nome')->get();
        $pacientes = Paciente::orderBy('nome')->get();

        $consultas = Consulta::with(['paciente', 'prontuario'])->get();

        //return view('agenda.index', compact('consultas'));
        return view('agenda.index', compact('medicos', 'pacientes', 'consultas'));
    }

    public function eventos(Request $request)
    {
        $query = Consulta::with('paciente', 'medico');

        // filtro por médico
        if ($request->medico_id) {
            $query->where('medico_id', $request->medico_id);
        }

        $consultas = $query->get();

        return response()->json(
            $consultas->map(function ($c) {

                // 🎨 paleta moderna
                $cores = match ($c->status) {
                    'agendada' => [
                        'bg' => '#3b82f6',   // azul moderno
                        'border' => '#2563eb'
                    ],
                    'atendida' => [
                        'bg' => '#22c55e',   // verde
                        'border' => '#16a34a'
                    ],
                    'cancelada' => [
                        'bg' => '#ef4444',   // vermelho
                        'border' => '#dc2626'
                    ],
                    default => [
                        'bg' => '#6b7280',
                        'border' => '#4b5563'
                    ]
                };

                return [
                    'id' => $c->id,

                    // título mais limpo
                    'title' => $c->paciente->nome,

                    'start' => $c->data . 'T' . $c->hora,

                    // 🎨 estilos visuais
                    'backgroundColor' => $cores['bg'],
                    'borderColor' => $cores['border'],
                    'textColor' => '#ffffff',

                    // info extra (pode usar no tooltip depois)
                    'extendedProps' => [
                        'medico' => $c->medico->nome,
                        'status' => $c->status
                    ],
                ];
            })
        );
    }

    public function atualizar(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);

        // Verificar conflito de horário
        $conflito = Consulta::where('medico_id', $consulta->medico_id)
            ->where('data', $request->data)
            ->where('hora', $request->hora)
            ->where('id', '!=', $id)
            ->exists();

        if ($conflito) {
            return response()->json(['erro' => 'Horário já ocupado'], 409);
        }

        $consulta->update([
            'data' => $request->data,
            'hora' => $request->hora
        ]);

        return response()->json(['sucesso' => true]);
    }
    public function show($id)
    {
        //return Consulta::findOrFail($id);
        $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])
            ->findOrFail($id);

        return view('prontuarios.show', compact('consulta'));
    }

    public function atualizarDetalhes(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);

        $consulta->update([
            'status' => $request->status,
            'observacoes' => $request->observacoes
        ]);

        return response()->json(['sucesso' => true]);
    }

    public function excluir($id)
    {
        Consulta::findOrFail($id)->delete();
        return response()->json(['sucesso' => true]);
    }
    public function criar(Request $request)
    {
        $conflito = Consulta::where([
            'medico_id' => $request->medico_id,
            'data' => $request->data,
            'hora' => $request->hora
        ])->exists();

        if ($conflito) {
            return response()->json(['erro' => 'Horário ocupado'], 409);
        }

        $consulta = Consulta::create([
            'paciente_id' => $request->paciente_id,
            'medico_id' => $request->medico_id,
            'data' => $request->data,
            'hora' => $request->hora,
            'status' => 'agendada',
            'observacoes' => $request->observacoes
        ]);

        return response()->json($consulta);
    }
}
