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

    public function eventos()
    {
        $consultas = Consulta::with('paciente', 'medico')->get();

        return response()->json(
            $consultas->map(function ($c) {

                $cor = match ($c->status) {
                    'agendada' => '#f0ad4e',
                    'atendida' => '#5cb85c',
                    'cancelada' => '#d9534f',
                };

                return [
                    'id' => $c->id,
                    'title' => $c->paciente->nome . ' - ' . $c->medico->nome,
                    'start' => $c->data . 'T' . $c->hora,
                    'color' => $cor,
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
        return Consulta::findOrFail($id);
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
