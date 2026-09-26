<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FiadoPagamento extends Model
{
    protected $table = 'fiado_pagamentos';
    protected $fillable = [
        'fiado_id', 'valor', 'data_pagamento',
    ];

    public function fiado()
    {
        return $this->belongsTo(Fiado::class);
    }
}
