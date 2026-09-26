<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OsItem extends Model
{
    protected $table = 'os_itens';

    protected $fillable = [
        'ordem_servico_id', 'produto_id', 'servico_id',
        'descricao', 'quantidade', 'valor_unitario'
    ];

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'ordem_servico_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    // Se tiver descrição escrita, ela tem prioridade de exibição
    // (mesmo que também tenha um serviço do catálogo vinculado)
    public function nomeItem(): string
    {
        return $this->produto?->nome
            ?? $this->descricao
            ?? $this->servico?->nome
            ?? 'Item removido';
    }
}