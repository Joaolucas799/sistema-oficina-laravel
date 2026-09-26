<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_id', 'tipo', 'quantidade', 'motivo', 'user_id',
        'e_venda', 'valor_venda', 'fornecedor_id', 'valor_unitario',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}