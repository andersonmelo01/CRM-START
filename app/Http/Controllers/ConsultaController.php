<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarLembreteConsulta;
use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Bloqueio;
use App\Models\Pagamento;
use App\Services\WhatsAppService;
//use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultaAgendadaMail;
use App\Jobs\EnviarLembreteConsultaEmail;
use Carbon\Carbon;



class ConsultaController extends Controller
{
    public function index(Request $request)
    {
        // Datas que possuem consultas criadas
        $datasDisponiveis = Consulta::selectRaw('DATE(data) as data')
            ->distinct()
            ->orderBy('data')
            ->pluck('data')
            ->toArray();

        $hoje = now()->toDateString();

        // Se veio data do filtro, usa ela.
        // Senão, usa hoje.
        $data = $request->get('data', $hoje);

        // Buscar consultas da data escolhida (mesmo que não tenha nenhuma)
        $consultas = Consulta::with(['paciente', 'medico', 'prontuario'])
            ->whereDate('data', $data)
            ->orderBy('hora')
            ->get();

        return view('consultas.index', [
            'consultas' => $consultas,
            'data' => $data,
            'datasDisponiveis' => $datasDisponiveis
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
                $q->whereNull('hora_inicio')
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
        $existe = Consulta::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where('hora', $hora) // ✅ usar a hora normalizada
            ->where('status', '!=', 'cancelada')
            ->exists();


        if ($existe) {
            return back()
                ->withErrors(['hora' => 'Horário já ocupado'])
                ->withInput();
        }

        $consultaCriada = null;

        DB::transaction(function () use ($request, &$consultaCriada) {

            // ✅ CRIA CONSULTA
            $consultaCriada = Consulta::create([
                'paciente_id' => $request->paciente_id,
                'medico_id'   => $request->medico_id,
                'data'        => $request->data,
                'hora'        => $request->hora,
                'status'      => 'agendada',
                'observacoes' => $request->observacoes,
            ]);

            // 💰 PAGAMENTO
            Pagamento::create([
                'consulta_id'     => $consultaCriada->id,
                'valor'           => $request->valor,
                'valor_pago'      => $request->valor_pago,
                'forma_pagamento' => $request->forma_pagamento,
                'status'          => $request->status_pagamento,
                'data_pagamento'  => $request->status_pagamento === 'pago'
                    ? now()
                    : null,
            ]);
        });

        // ================= LEMBRETE EMAIL (30 MIN) =================
        $inicio = Carbon::parse($consultaCriada->data . ' ' . $consultaCriada->hora);
        $envio  = $inicio->copy()->subMinutes(30);

        if ($envio->isFuture()) {
            \App\Jobs\EnviarLembreteConsultaEmail::dispatch($consultaCriada)->delay($envio);
        }

        // ================= EMAIL IMEDIATO =================
        $consultaCriada->load(['paciente', 'medico']);

        if ($consultaCriada->paciente && $consultaCriada->paciente->email) {
            try {
                \Mail::to($consultaCriada->paciente->email)
                    ->send(new \App\Mail\ConsultaAgendadaMail($consultaCriada));
            } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                // Loga o erro, mas não trava a página
                \Log::error('Erro ao enviar email: ' . $e->getMessage());
            }
        }


        return redirect()
            ->route('consultas.index')
            ->with('success', 'Consulta agendada e e-mail enviado!');
    }

    public function update(Request $request, Consulta $consulta)
    {
        $consulta->update([
            'status'  => $request->status,
            'retorno' => $request->retorno ?? false
        ]);

        // Envia email somente quando virar "agendada"
        if ($consulta->status === 'agendada') {

            // ================= EMAIL IMEDIATO =================
            $consulta->load(['paciente', 'medico']);

            if ($consulta->paciente && $consulta->paciente->email) {
                try {
                    \Mail::to($consulta->paciente->email)
                        ->send(new \App\Mail\ConsultaAgendadaMail($consulta));
                } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                    \Log::error('Erro ao enviar email: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Agendamento atualizado com sucesso!');
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

    public function whatsapp(Consulta $consulta)
    {
        $consulta->load(['paciente', 'medico']);

        $paciente = $consulta->paciente;
        $medico   = $consulta->medico;

        if (!$paciente || !$paciente->telefone) {
            return back()->with('error', 'Paciente sem telefone cadastrado.');
        }

        $dataHora = \Carbon\Carbon::parse(
            $consulta->data . ' ' . $consulta->hora
        )->format('d/m/Y H:i');

        $mensagem = "📅 *Consulta Agendada!*\n\n"
            . "Paciente: {$paciente->nome}\n"
            . "Médico: {$medico->nome}\n"
            . "Data: {$dataHora}\n\n"
            . "Qualquer dúvida, entre em contato.";

        $telefone = preg_replace('/\D/', '', $paciente->telefone);

        // adiciona DDI Brasil se não tiver
        if (substr($telefone, 0, 2) !== '55') {
            $telefone = '55' . $telefone;
        }

        $link = "https://wa.me/{$telefone}?text=" . urlencode($mensagem);

        // ================= EMAIL IMEDIATO =================
        //$consultaCriada = null;
        //$consultaCriada->load(['paciente', 'medico']);

        if ($consulta->paciente && $consulta->paciente->email) {
            try {
                \Mail::to($consulta->paciente->email)
                    ->send(new \App\Mail\ConsultaAgendadaMail($consulta));
            } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                // Loga o erro, mas não trava a página
                \Log::error('Erro ao enviar email: ' . $e->getMessage());
            }
        }

        return redirect()->away($link);
    }
    public function whatsappPreCadastroConsulta(Consulta $consulta)
    {
        $consulta->load(['paciente', 'medico']);

        $paciente = $consulta->paciente;
        $medico   = $consulta->medico;

        if (!$paciente || !$paciente->telefone) {
            return back()->with('error', 'Paciente sem telefone cadastrado.');
        }

        $dataHora = \Carbon\Carbon::parse(
            $consulta->data . ' ' . $consulta->hora
        )->format('d/m/Y H:i');

        $mensagem = "📅 *Precisamos confirmar sua consulta*\n\n"
            . "Paciente: {$paciente->nome}\n"
            . "Médico: {$medico->nome}\n"
            . "Data: {$dataHora}\n\n"
            . "Se confirma digita: CONFIRMADO\n"
            . "Se não confirma digite: NÂO-CONFIRMADO\n"
            . "Qualquer dúvida, entre em contato.";

        $telefone = preg_replace('/\D/', '', $paciente->telefone);

        // adiciona DDI Brasil se não tiver
        if (substr($telefone, 0, 2) !== '55') {
            $telefone = '55' . $telefone;
        }

        $link = "https://wa.me/{$telefone}?text=" . urlencode($mensagem);

        return redirect()->away($link);
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
                'mensagem'   => 'Horário bloqueado para este médico'
            ]);
        }

        // Verifica conflito com consultas já marcadas
        $ocupado = Consulta::where('medico_id', $request->medico_id)
            ->where('data', $request->data)
            ->where('hora', $hora)
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($ocupado) {
            return response()->json([
                'disponivel' => false,
                'mensagem'   => 'Horário já ocupado'
            ]);
        }

        return response()->json([
            'disponivel' => true,
            'mensagem'   => 'Horário disponível'
        ]);
    }
}
