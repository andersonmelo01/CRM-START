<?php

namespace App\Models;

use App\Models\Medico;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'data',
        'hora',
        'status',
        'observacoes'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function prontuario()
    {
        return $this->hasOne(Prontuario::class);
    }
    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }
}
