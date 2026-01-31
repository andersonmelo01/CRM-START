<?php

namespace App\Models;

use App\Models\Consulta;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = [
        'nome',
        'crm',
        'especialidade',
        'telefone',
        'email'
    ];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
    
    public function bloqueios()
    {
        return $this->hasMany(Bloqueio::class);
    }

    public function estaBloqueadoEm($data, $hora): bool
    {
        return $this->bloqueios()
            ->where('data', $data)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fim', '>=', $hora)
            ->exists();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
