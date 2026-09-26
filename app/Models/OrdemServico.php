<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    protected $table = 'ordens_servico';

    protected $fillable = [
        'cliente_id', 'veiculo_id', 'status',
        'valor_total', 'observacoes', 'data_conclusao'
    ];

    protected $casts = [
        'data_conclusao' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function itens()
    {
        return $this->hasMany(OsItem::class, 'ordem_servico_id');
    }

    public function recalcularTotal(): void
    {
        $total = $this->itens->sum(fn ($item) => $item->quantidade * $item->valor_unitario);

        $this->update(['valor_total' => $total]);
    }
}