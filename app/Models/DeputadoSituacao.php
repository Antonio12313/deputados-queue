<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeputadoSituacao extends Model
{
    protected $table = 'deputados_situacoes';

    protected $fillable = [
        'cod',
        'sigla',
        'nome',
        'descricao',
    ];

    protected $casts = [
        'descricao' => 'string',
    ];

    public function deputados(): HasMany
    {
        return $this->hasMany(Deputado::class, 'situacao', 'sigla');
    }

}
