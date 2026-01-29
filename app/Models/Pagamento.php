<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        'consulta_id',
        'valor',
        'valor_pago',
        'forma_pagamento',
        'status',
        'data_pagamento'
    ];
    protected $casts = [
        'data_pagamento' => 'datetime',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
