<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        return $this->belongsTo(Deputado::class, 'deputado_id', 'id_deputado')->withTrashed();
    }

    public function scopeByTipo($query, $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }
}
