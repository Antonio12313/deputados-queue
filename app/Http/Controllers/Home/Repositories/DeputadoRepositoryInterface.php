<?php

namespace App\Http\Controllers\Home\Repositories;

use App\Models\Deputado;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface DeputadoRepositoryInterface
{
    public function getDeputados(): EloquentBuilder;
    public function getDeputadoWithDetails(int $id): ?Deputado;
    public function getDeputadosForListing(): EloquentBuilder;
    public function getAllPartidos(): array;
    public function getAllSituacoes(): array;
    public function getAllUfs(): array;

}
