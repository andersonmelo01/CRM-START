<?php

namespace App\Jobs;

use App\Models\Consulta;
use App\Mail\ConsultaAgendadaMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarLembreteConsultaEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $consulta;

    public function __construct(Consulta $consulta)
    {
        $this->consulta = $consulta;
    }

    public function handle(): void
    {
        $this->consulta->load(['paciente', 'medico']);

        if ($this->consulta->paciente && $this->consulta->paciente->email) {
            Mail::to($this->consulta->paciente->email)
                ->send(new ConsultaAgendadaMail($this->consulta));
        }
    }
}
