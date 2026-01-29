<?php

namespace App\Models;

use App\Models\Consulta;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'nome',
        'data_nascimento',
        'cpf',
        'telefone',
        'email',
        'endereco'
    ];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}
