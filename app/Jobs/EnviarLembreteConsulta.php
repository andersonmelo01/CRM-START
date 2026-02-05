<?php

namespace App\Jobs;

use App\Models\Consulta;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class EnviarLembreteConsulta implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Consulta $consulta;

    // 👇 AQUI
    public function __construct(Consulta $consulta)
    {
        $this->consulta = $consulta;
    }

    public function handle()
    {
        // 👇 AQUI ELE EXISTE
        $consulta = $this->consulta;

        $paciente = $consulta->paciente;
        $medico   = $consulta->medico;

        if (!$paciente || !$paciente->telefone) {
            return;
        }

        $dataHora = Carbon::parse(
            $consulta->data . ' ' . $consulta->hora
        )->format('d/m/Y H:i');

        $mensagem = "⏰ *Lembrete de Consulta*\n\n"
            . "Paciente: {$paciente->nome}\n"
            . "Médico: {$medico->nome}\n"
            . "Data: {$dataHora}\n\n"
            . "A consulta começa em 30 minutos.";

        WhatsAppService::send($paciente->telefone, $mensagem);
    }
}
