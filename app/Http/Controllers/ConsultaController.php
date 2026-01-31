<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Bloqueio;
use App\Models\Pagamento;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function index(Request $request)
    {
        // Data selecionada ou hoje
        $data = $request->get('data', now()->toDateString());

        // BUSCA AS CONSULTAS (ISSO ESTAVA FALTANDO)
        $consultas = Consulta::with(['paciente', 'medico', 'prontuario'])
            ->where('data', $data)
            ->orderBy('hora')
            ->get();

        // ENVIA PARA A VIEW
        return view('consultas.index', [
            'consultas' => $consultas,
            'data' => $data
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->get('data', now()->toDateString());
        $pacientes = Paciente::all();
        $medicos = Medico::all();

        return view('consultas.create', compact('data', 'pacientes', 'medicos'));


        /*return view('consultas.index', [
            'pacientes' => Paciente::orderBy('nome')->get(),
            'medicos' => Medico::orderBy('nome')->get(),
        ]);*/
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'medico_id'        => 'required|exists:medicos,id',
            'data'             => 'required|date',
            'hora'             => 'required',
            'valor'            => 'required|numeric',
            'forma_pagamento'  => 'required',
            'status_pagamento' => 'required|in:pago,pendente',
        ]);

        // ⏰ normaliza a hora
        $hora = Carbon::createFromFormat('H:i', $request->hora)->format('H:i:s');

        // 🔒 verifica bloqueio do médico
        $bloqueado = Bloqueio::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where(function ($q) use ($hora) {
                $q->whereNull('hora_inicio') // bloqueio do dia todo
                    ->orWhere(function ($q) use ($hora) {
                        $q->where('hora_inicio', '<=', $hora)
                            ->where('hora_fim', '>=', $hora);
                    });
            })
            ->exists();

        if ($bloqueado) {
            return back()
                ->withErrors(['hora' => 'Horário bloqueado para este médico'])
                ->withInput();
        }

        // ❌ conflito de consulta
        $existe = Consulta::where([
            'medico_id' => $request->medico_id,
            'data'      => $request->data,
            'hora'      => $request->hora
        ])->exists();

        if ($existe) {
            return back()
                ->withErrors(['hora' => 'Horário bloqueado para este médico'])
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            $consulta = Consulta::create([
                'paciente_id' => $request->paciente_id,
                'medico_id'   => $request->medico_id,
                'data'        => $request->data,
                'hora'        => $request->hora,
                'status'      => 'agendada',
                'observacoes' => $request->observacoes,
            ]);

            Pagamento::create([
                'consulta_id'    => $consulta->id,
                'valor'          => $request->valor,
                'valor_pago'     => $request->valor_pago,
                'forma_pagamento' => $request->forma_pagamento,
                'status'         => $request->status_pagamento,
                'data_pagamento' => $request->status_pagamento === 'pago'
                    ? now()
                    : null,
            ]);
        });

        return redirect()->route('consultas.index');
    }

    public function update(Request $request, Consulta $consulta)
    {
        $consulta->update([
            'status' => $request->status,
            'retorno' => $request->retorno ?? false
        ]);

        return back();
    }

    public function destroy(Consulta $consulta)
    {
        $consulta->delete();
        return back();
    }

    public function show(Consulta $consulta)
    {
        $consulta->load(['paciente', 'medico', 'prontuario']);

        return view('consultas.show', compact('consulta'));
    }
}
