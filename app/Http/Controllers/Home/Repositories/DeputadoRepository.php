<?php

namespace App\Http\Controllers\Home\Repositories;

use App\Models\Deputado;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class DeputadoRepository implements DeputadoRepositoryInterface
{
    public function getDeputados(): EloquentBuilder
    {
        return Deputado::query()
            ->with(['gabinete', 'legislaturaAtual', 'despesas', 'situacao'])
            ->orderBy('nome');
    }

    public function getSelectFields(): array
    {
        return [
            'id',
            'id_deputado',
            'nome',
            'nome_eleitoral',
            'siglaPartido',
            'siglaUf',
            'url_foto',
            'email',
            'situacao',
        ];
    }

    public function getDetailFields(): array
    {
        return [
            'id',
            'id_deputado',
            'nome',
            'nome_eleitoral',
            'siglaPartido',
            'siglaUf',
            'url_foto',
            'email',
            'situacao',
            'data_nascimento',
            'escolaridade',
            'municipio_nascimento',
            'uf_nascimento',
        ];
    }

    public function getDeputadosForListing(): EloquentBuilder
    {
        return $this->getDeputados();
    }

    public function getDeputadoWithDetails(int $id): ?Deputado
    {
        return Deputado::query()
            ->withRelations()
            ->find($id);
    }


    public function getAllPartidos(): array
    {
        return Deputado::whereNotNull('siglaPartido')
            ->distinct()
            ->orderBy('siglaPartido')
            ->pluck('siglaPartido')
            ->toArray();
    }

    public function getAllUfs(): array
    {
        return Deputado::whereNotNull('siglaUf')
            ->distinct()
            ->orderBy('siglaUf')
            ->pluck('siglaUf')
            ->toArray();
    }

    public function getAllSituacoes(): array
    {
        return Deputado::whereNotNull('situacao')
            ->distinct()
            ->orderBy('situacao')
            ->pluck('situacao')
            ->toArray();
    }
}
