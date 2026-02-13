<?php

namespace App\Mail;

use App\Models\Consulta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ConsultaAgendadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $consulta;

    public function __construct(Consulta $consulta)
    {
        $this->consulta = $consulta;
    }

    public function build()
    {
        $dataHora = Carbon::parse(
            $this->consulta->data . ' ' . $this->consulta->hora
        )->format('d/m/Y H:i');

        return $this->subject('Consulta Agendada')
            ->view('emails.consulta_agendada')
            ->with([
                'paciente' => $this->consulta->paciente,
                'medico'   => $this->consulta->medico,
                'dataHora' => $dataHora,
            ]);
    }
}
