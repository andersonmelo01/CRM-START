<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prontuario extends Model
{
    protected $fillable = [
        'consulta_id',
        'queixa_principal',
        'historico_doenca',
        'exame_fisico',
        'diagnostico',
        'conduta',
        'prescricao'
    ];
    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
