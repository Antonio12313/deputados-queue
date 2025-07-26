<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeputadosGabinete extends Model
{

    protected $fillable = [
        'deputado_id',
        'nome',
        'predio',
        'sala',
        'andar',
        'telefone',
        'email',
    ];

    public function deputado(): BelongsTo
    {
        $this->belongsTo(Deputado::class, 'deputado_id')->withTrashed();
    }
}
