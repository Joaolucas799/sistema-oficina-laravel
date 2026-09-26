<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
    use HasFactory;

    protected $table = 'contas_pagar';

    protected $fillable = [
        'fornecedor_id',
        'entrada_estoque_id',
        'descricao',
        'valor_total',
        'valor_pago',
        'data_vencimento',
        'status',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'valor_total' => 'decimal:2',
        'valor_pago' => 'decimal:2',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(PagamentoContaPagar::class);
    }

    public function getValorRestanteAttribute()
    {
        return $this->valor_total - $this->valor_pago;
    }

    public function getVencidaAttribute()
    {
        return $this->status !== 'pago' && $this->data_vencimento->isPast();
    }

    public function atualizarStatus()
    {
        if ($this->valor_pago >= $this->valor_total) {
            $this->status = 'pago';
        } elseif ($this->valor_pago > 0) {
            $this->status = 'parcial';
        } else {
            $this->status = 'pendente';
        }
        $this->save();
    }
}