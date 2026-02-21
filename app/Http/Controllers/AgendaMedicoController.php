<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // <-- ESTA LINHA É OBRIGATÓRIA
use Illuminate\Http\Request;
use App\Models\AgendaMedico;
use App\Models\Consulta;
use App\Models\Medico;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AgendaMedicoController extends Controller
{
    
    public function index()
    {
        $agendas = AgendaMedico::with('medico')
            ->orderBy('data')
            ->get();

        return view('agenda_medicos.index', compact('agendas'));
    }

    public function create()
    {
        $medicos = Medico::orderBy('nome')->get();
        return view('agenda_medicos.create', compact('medicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required|after:hora_inicio',
            'intervalo' => 'required|integer|min:5'
        ]);

        AgendaMedico::create($request->all());

        return redirect()
            ->route('agenda-medicos.index')
            ->with('success', 'Agenda cadastrada com sucesso!');
    }

    public function destroy(AgendaMedico $agendaMedico)
    {
        $agendaMedico->delete();

        return back()->with('success', 'Agenda removida!');
    }

    public function disponivel($medicoId)
    {
        $agendas = AgendaMedico::where('medico_id', $medicoId)
            ->whereDate('data', '>=', Carbon::today())
            ->orderBy('data')
            ->get();

        $resultado = [];

        foreach ($agendas as $agenda) {

            // horários já ocupados para este médico na data
            $horariosOcupados = Consulta::where('medico_id', $medicoId)
                ->whereDate('data', $agenda->data)
                ->pluck('hora')
                ->toArray();

            $inicio = Carbon::parse($agenda->hora_inicio);
            $fim = Carbon::parse($agenda->hora_fim);

            while ($inicio < $fim) {
                $hora = $inicio->format('H:i');

                if (!in_array($hora, $horariosOcupados)) {
                    // inicializa array da data se não existir
                    if (!isset($resultado[$agenda->data])) {
                        $resultado[$agenda->data] = [];
                    }

                    // adiciona horário sem duplicar
                    if (!in_array($hora, array_column($resultado[$agenda->data], 'hora'))) {
                        $resultado[$agenda->data][] = ['hora' => $hora];
                    }
                }

                $inicio->addMinutes($agenda->intervalo);
            }
        }

        // ordena horários de cada data
        foreach ($resultado as &$horarios) {
            usort($horarios, fn($a, $b) => strcmp($a['hora'], $b['hora']));
        }

        return response()->json($resultado);
    }

    public function horarios(Request $request)
    {
        $agenda = AgendaMedico::where('medico_id', $request->medico_id)
            ->whereDate('data', $request->data)
            ->first();

        if (!$agenda) return response()->json([]);

        $ocupados = Consulta::where('medico_id', $request->medico_id)
            ->whereDate('data', $request->data)
            ->pluck('hora')
            ->toArray();

        $horarios = [];
        $inicio = Carbon::parse($agenda->hora_inicio);
        $fim = Carbon::parse($agenda->hora_fim);

        while ($inicio < $fim) {
            $horaFormatada = $inicio->format('H:i');

            if (!in_array($horaFormatada, $ocupados)) {
                $horarios[] = $horaFormatada;
            }

            $inicio->addMinutes($agenda->intervalo);
        }

        return response()->json($horarios);
    }
}
