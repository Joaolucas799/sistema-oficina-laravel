<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Fiado extends Model
{
    protected $fillable = [
        'cliente_id', 'origem', 'descricao', 'valor_total', 'status', 'data', 'ordem_servico_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'ordem_servico_id');
    }

    public function pagamentos()
    {
        return $this->hasMany(FiadoPagamento::class);
    }

    public function itens()
    {
        return $this->hasMany(FiadoItem::class);
    }

    public function valorPago()
    {
        return $this->pagamentos->sum('valor');
    }

    public function valorRestante()
    {
        return $this->valor_total - $this->valorPago();
    }
}
