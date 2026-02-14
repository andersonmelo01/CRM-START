<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Consulta;
use Carbon\Carbon;
use App\Models\Paciente;


class AgendamentoPublicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::orderBy('nome')->get();
        return view('agendamento-publico', compact('medicos'));
    }

    public function horarios(Request $request)
    {
        $data   = $request->data;
        $medico = $request->medico_id;

        $horarios = collect([
            '08:00',
            '08:30',
            '09:00',
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30',
            '17:00'
        ]);

        $ocupados = Consulta::where('medico_id', $medico)
            ->where('data', $data)
            ->pluck('hora');

        return response()->json(
            $horarios->diff($ocupados)->values()
        );
    }

    public function store(Request $request)
    {
        // Limpa o CPF (remove pontos e traços)
        $cpf = preg_replace('/\D/', '', $request->cpf);

        // Verifica se já existe paciente com esse CPF
        $paciente = Paciente::where('cpf', $cpf)->first();

        // Se NÃO existir, bloqueia o agendamento
        if (!$paciente) {
            return back()->withErrors([
                'cpf' => 'CPF não cadastrado. Faça o pré-cadastro antes de agendar.'
            ])->withInput();
        }

        // Cria a consulta com status de PRÉ-CADASTRO
        Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id'   => $request->medico_id,
            'data'        => $request->data,
            'hora'        => $request->hora,
            'status'      => 'pre-cadastro', // 👈 ALTERADO AQUI
            'observacoes' => $request->observacoes,
        ]);

        return back()->with('success', 'Agendamento realizado com sucesso! Aguardando confirmação da clínica.');
    }


    public function verificarCpf(Request $request)
    {
        $cpf = preg_replace('/\D/', '', $request->cpf);

        $paciente = Paciente::where('cpf', $cpf)->first();

        if ($paciente) {
            return response()->json([
                'existe' => true,
                'nome' => $paciente->nome,
                'telefone' => $paciente->telefone,
                'email' => $paciente->email,
                'data_nascimento' => $paciente->data_nascimento,
            ]);
        }

        return response()->json(['existe' => false]);
    }
    
}

