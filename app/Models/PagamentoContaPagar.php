<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoContaPagar extends Model
{
    use HasFactory;

    protected $table = 'pagamentos_contas_pagar';

    protected $fillable = ['conta_pagar_id', 'valor', 'data_pagamento'];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function contaPagar()
    {
        return $this->belongsTo(ContaPagar::class);
    }
}