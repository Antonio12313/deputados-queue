<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeputadosRedesSocial extends Model
{
    protected $fillable = [
        'deputado_id',
        'url',
        'tipo',
    ];

    public function deputado(): BelongsTo
    {
        $this->belongsTo(Deputado::class, 'deputado_id')->withTrashed();
    }
}
