<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FiadoItem extends Model
{
    protected $table = 'fiado_itens';
    protected $fillable = [
        'fiado_id', 'descricao', 'valor',
    ];

    public function fiado()
    {
        return $this->belongsTo(Fiado::class);
    }
}
