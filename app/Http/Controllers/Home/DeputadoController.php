<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\Repositories\DeputadoRepositoryInterface;
use App\Http\Controllers\Home\Resource\DeputadoResource;
use App\Http\Controllers\Home\Services\DeputadoFilterService;
use App\Models\Deputado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeputadoController extends Controller
{
    public function __construct(
        readonly DeputadoRepositoryInterface $deputadoRepository,
        readonly DeputadoFilterService $filterService,
    ) {
    }

    public function index(Request $request): Response
    {
        $query = $this->deputadoRepository->getDeputadosForListing();

        $filteredQuery = $this->filterService->applyFilters($query, $request);

        $deputados = $filteredQuery
            ->paginate(10)
            ->appends($request->except('page'))
            ->through(fn(Deputado $deputado) => new DeputadoResource($deputado));

        $filters = $this->filterService->getFiltersFromRequest($request);

        $partidos = $this->deputadoRepository->getAllPartidos();
        $ufs = $this->deputadoRepository->getAllUfs();

        return Inertia::render('dashboard', [
            'deputados' => $deputados,
            'filters' => $filters,
            'filterOptions' => [
                'partidos' => $partidos,
                'ufs' => $ufs,
            ]
        ]);
    }
}
