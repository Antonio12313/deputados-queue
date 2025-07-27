<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeputadoDespesa extends Model
{
    protected $table = 'deputados_despesas';

    protected $fillable = [
        'deputado_id',
        'ano',
        'cnpj_cpf_fornecedor',
        'cod_documento',
        'cod_lote',
        'cod_tipo_documento',
        'data_documento',
        'mes',
        'nome_fornecedor',
        'num_documento',
        'num_ressarcimento',
        'parcela',
        'tipo_despesa',
        'tipo_documento',
        'url_documento',
        'valor_documento',
        'valor_glosa',
        'valor_liquido',
    ];

    protected $casts = [
        'ano' => 'integer',
        'cod_documento' => 'integer',
        'cod_lote' => 'integer',
        'cod_tipo_documento' => 'integer',
        'data_documento' => 'date',
        'mes' => 'integer',
        'parcela' => 'integer',
        'valor_documento' => 'decimal:2',
        'valor_glosa' => 'decimal:2',
        'valor_liquido' => 'decimal:2',
    ];

    public function deputado(): BelongsTo
    {
        return $this->belongsTo(Deputado::class, 'deputado_id', 'id_deputado');
    }
}
