<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'email',
        'observacao',
    ];

    /**
     * Contas a pagar vinculadas a este fornecedor
     */
    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class);
    }
}