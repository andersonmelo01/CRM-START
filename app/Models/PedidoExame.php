<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoExame extends Model
{
    protected $table = 'pedidos_exames'; // ← ADD ISSO
    
    protected $fillable = [
        'consulta_id',
        'prontuario_id',
        'tipo_exame',
        'descricao',
        'data_solicitacao',
        'status',
        'resultado'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }
}
