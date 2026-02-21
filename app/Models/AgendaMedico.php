<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaMedico extends Model
{
    protected $table = 'agenda_medicos';

    protected $fillable = [
        'medico_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'intervalo',
        'ativo'
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}
