<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome', 'codigo', 'categoria_id', 'fornecedor_id',
        'unidade', 'preco_custo', 'preco_venda',
        'estoque_atual', 'estoque_minimo'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }

    // Método auxiliar: retorna true se o estoque está no ou abaixo do mínimo
    public function estoqueBaixo(): bool
    {
        return $this->estoque_atual <= $this->estoque_minimo;
    }
}