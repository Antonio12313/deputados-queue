<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deputado extends Model
{
    use SoftDeletes;

    protected $fillable = [
            'id_deputado',
            'id_legislatura',
            'nome',
            'email',
            'siglaPartido',
            'siglaUf',
            'uriPartido',
            'uri',
            'cpf',
            'nome_civil',
            'data_nascimento',
            'data_falecimento',
            'sexo',
            'escolaridade',
            'municipio_nascimento',
            'uf_nascimento',
            'url_website',
            'condicao_eleitoral',
            'data_ultimo_status',
            'descricao_status',
            'nome_eleitoral',
            'situacao',
            'url_foto',
            'detalhes_carregados',
            'detalhes_atualizados_em'
    ];

    protected $casts = [
            'id_deputado' => 'integer',
            'id_legislatura' => 'integer',
            'data_nascimento' => 'date',
            'data_falecimento' => 'date',
            'data_ultimo_status' => 'datetime',
            'detalhes_carregados' => 'boolean',
            'detalhes_atualizados_em' => 'timestamp',
    ];


    public function gabinete(): HasMany
    {
        return $this->hasMany(DeputadosGabinete::class, 'id_deputado');
    }

    public function redesSocial(): HasMany
    {
        return $this->hasMany(DeputadosRedesSocial::class, 'id_deputado');
    }
}
