<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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
        'url_foto'
    ];

    protected $casts = [
        'id_deputado' => 'integer',
        'id_legislatura' => 'integer',
        'data_nascimento' => 'date',
        'data_falecimento' => 'date',
        'data_ultimo_status' => 'datetime',
    ];

    public function gabinete(): HasOne
    {
        return $this->hasOne(DeputadosGabinete::class, 'deputado_id', 'id_deputado');
    }

    public function legislaturaAtual(): HasMany
    {
        return $this->hasMany(DeputadoLegislatura::class)->orderBy('id_legislatura', 'desc');
    }

    public function redesSociais(): HasMany
    {
        return $this->hasMany(DeputadosRedesSocial::class, 'deputado_id', 'id_deputado');
    }

    public function despesas(): HasMany
    {
        return $this->hasMany(DeputadoDespesa::class, 'deputado_id', 'id_deputado');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(DeputadoSituacao::class, 'situacao', 'sigla');
    }

    public function scopeComDetalhes($query)
    {
        return $query->where('detalhes_carregados', true);
    }

    public function scopeSemDetalhes($query)
    {
        return $query->where('detalhes_carregados', false);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nome', 'LIKE', "%{$search}%")
                ->orWhere('nome_eleitoral', 'LIKE', "%{$search}%");
        });
    }

    public function scopeByPartido(Builder $query, string $partido): Builder
    {
        return $query->where('siglaPartido', $partido);
    }

    public function scopeByUf(Builder $query, string $uf): Builder
    {
        return $query->where('siglaUf', $uf);
    }

    public function scopeBySituacao(Builder $query, string $situacao): Builder
    {
        return $query->where('situacao', $situacao);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['gabinete', 'situacao']);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('nome');
    }
}
