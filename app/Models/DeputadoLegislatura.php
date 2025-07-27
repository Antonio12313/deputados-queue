<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeputadoLegislatura extends Model
{
    protected $table = 'deputado_legislatura';

    protected $fillable = [
        'deputado_id',
        'id_legislatura',
        'siglaPartido',
        'siglaUf',
        'uriPartido',
        'uri',
    ];

    public function deputado(): BelongsTo
    {
        return $this->belongsTo(Deputado::class)->withTrashed();
    }
}
