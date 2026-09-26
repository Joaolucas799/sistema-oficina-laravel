<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $fillable = ['cliente_id', 'placa', 'marca', 'modelo', 'ano'];

    // Um veículo pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
