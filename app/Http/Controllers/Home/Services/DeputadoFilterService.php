<?php

namespace App\Http\Controllers\Home\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DeputadoFilterService
{
    public function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->filled('partido'), function (Builder $query) use ($request) {
                $query->byPartido($request->partido);
            })
            ->when($request->filled('uf'), function (Builder $query) use ($request) {
                $query->byUf($request->uf);
            })
            ->when($request->filled('situacao'), function (Builder $query) use ($request) {
                $query->bySituacao($request->situacao);
            });
    }

    public function getFiltersFromRequest(Request $request): array
    {
        return [
            'search' => $request->search,
            'partido' => $request->partido,
            'uf' => $request->uf,
            'situacao' => $request->situacao,
        ];
    }

}
