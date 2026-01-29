<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloqueio extends Model
{
    protected $table = 'bloqueios';
    protected $fillable = [
        'medico_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'motivo'
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}
