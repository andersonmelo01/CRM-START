<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emitente extends Model
{
    protected $fillable = [
        'nome',
        'documento',
        'telefone',
        'endereco',
        'cidade',
        'uf',
        'mensagem_rodape',
        'ativo'
    ];
}
