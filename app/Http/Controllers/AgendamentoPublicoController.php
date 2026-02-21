<?php

namespace App\Http\Controllers;

use App\Models\AgendaMedico;
use App\Models\Bloqueio;
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
        $medicoId = $request->medico_id;
        $data = $request->data;

        $diaSemana = Carbon::parse($data)->dayOfWeek; // 0 a 6

        $agenda = AgendaMedico::where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->first();

        if (!$agenda) {
            return response()->json([]);
        }

        // horários já ocupados (tabela de consultas)
        $ocupados = Consulta::where('medico_id', $medicoId)
            ->whereDate('data', $data)
            ->pluck('hora')
            ->toArray();

        $horarios = [];

        $inicio = Carbon::parse($agenda->hora_inicio);
        $fim = Carbon::parse($agenda->hora_fim);

        while ($inicio->lt($fim)) {
            $hora = $inicio->format('H:i');

            if (!in_array($hora, $ocupados)) {
                $horarios[] = $hora;
            }

            $inicio->addMinutes($agenda->intervalo);
        }

        return response()->json($horarios);
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

        // ⏰ normaliza a hora
        $hora = Carbon::createFromFormat('H:i', $request->hora)->format('H:i:s');

        // 🔒 verifica bloqueio do médico
        $bloqueado = Bloqueio::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where(function ($q) use ($hora) {
                $q->whereNull('hora_inicio')
                    ->orWhere(function ($q) use ($hora) {
                        $q->where('hora_inicio', '<=', $hora)
                            ->where('hora_fim', '>=', $hora);
                    });
            })
            ->exists();

        if ($bloqueado) {
            return back()->withErrors([
                'hora' => 'Horário bloqueado para este médico'
            ])->withInput();
        }

        // ❌ verifica se já existe consulta nesse horário
        $ocupado = Consulta::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where('hora', $hora)
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($ocupado) {
            return back()->withErrors([
                'hora' => 'Horário já ocupado. Escolha outro horário.'
            ])->withInput();
        }

        // ✅ Cria a pré-consulta
        $consultaCriada = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id'   => $request->medico_id,
            'data'        => $request->data,
            'hora'        => $request->hora,
            'status'      => 'pre-cadastro',
            'observacoes' => $request->observacoes,
        ]);

        // ================= EMAIL IMEDIATO =================
        $consultaCriada->load(['paciente', 'medico']);

        if ($consultaCriada->paciente && $consultaCriada->paciente->email) {
            try {
                \Mail::to($consultaCriada->paciente->email)
                    ->send(new \App\Mail\PreConsultaAgendadaMail($consultaCriada));
            } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                \Log::error('Erro ao enviar email: ' . $e->getMessage());
            }
        }

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

    public function datas(Request $request)
    {
        $datas = AgendaMedico::where('medico_id', $request->medico_id)
            ->whereDate('data', '>=', now()->toDateString())
            ->pluck('data')
            ->unique()
            ->values();

        return response()->json($datas);
    }

    public function verificarHora(Request $request)
    {
        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data'      => 'required|date',
            'hora'      => 'required',
        ]);

        $hora = Carbon::createFromFormat('H:i', $request->hora)->format('H:i:s');

        // Verifica bloqueio
        $bloqueado = Bloqueio::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where(function ($q) use ($hora) {
                $q->whereNull('hora_inicio')
                    ->orWhere(function ($q) use ($hora) {
                        $q->where('hora_inicio', '<=', $hora)
                            ->where('hora_fim', '>=', $hora);
                    });
            })
            ->exists();

        if ($bloqueado) {
            return response()->json([
                'disponivel' => false,
                'mensagem' => 'Horário bloqueado para este médico'
            ]);
        }

        // Verifica se já existe consulta
        $ocupado = Consulta::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where('hora', $hora)
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($ocupado) {
            return response()->json([
                'disponivel' => false,
                'mensagem' => 'Horário já ocupado'
            ]);
        }

        return response()->json([
            'disponivel' => true,
            'mensagem' => 'Horário disponível'
        ]);
    }
}

