<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Deputado;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{

    public function index(): Response
    {
        $deputados = Deputado::with(['gabinete', 'situacao'])
            ->paginate(10)
            ->through(fn ($deputado) => [
                'id' => $deputado->id,
                'id_deputado' => $deputado->id_deputado,
                'nome' => $deputado->nome,
                'nome_eleitoral' => $deputado->nome_eleitoral,
                'siglaPartido' => $deputado->siglaPartido,
                'siglaUf' => $deputado->siglaUf,
                'url_foto' => $deputado->url_foto,
                'email' => $deputado->email,
                'situacao' => $deputado->situacao?->descricao ?? $deputado->situacao,
                'gabinete' => $deputado->gabinete,
                'total_despesas' => (float) $deputado->despesas()->sum('valor_liquido'),
                'quantidade_despesas' => $deputado->despesas()->count(),
                'data_nascimento' => $deputado->data_nascimento,
                'escolaridade' => $deputado->escolaridade,
            ]);

        return Inertia::render('dashboard', [
            'deputados' => $deputados,
        ]);
    }

    public function show($id)
    {
        $deputado = Deputado::with(['gabinete', 'redesSociais', 'despesas', 'situacao'])
            ->where('id_deputado', $id)
            ->firstOrFail();

        $despesas = $deputado->despesas()
            ->orderBy('data_documento', 'desc')
            ->paginate(10);

        return Inertia::render('dashboard', [
            'deputados' => null,
            'deputado_detalhes' => [
                'id' => $deputado->id,
                'id_deputado' => $deputado->id_deputado,
                'nome' => $deputado->nome,
                'nome_eleitoral' => $deputado->nome_eleitoral,
                'siglaPartido' => $deputado->siglaPartido,
                'siglaUf' => $deputado->siglaUf,
                'url_foto' => $deputado->url_foto,
                'email' => $deputado->email,
                'situacao' => $deputado->situacao?->descricao ?? $deputado->situacao,
                'gabinete' => $deputado->gabinete,
                'redesSociais' => $deputado->redesSociais,
                'data_nascimento' => $deputado->data_nascimento?->format('d/m/Y'),
                'escolaridade' => $deputado->escolaridade,
                'municipio_nascimento' => $deputado->municipio_nascimento,
                'uf_nascimento' => $deputado->uf_nascimento,
                'total_despesas' => $deputado->despesas()->sum('valor_liquido'),
            ],
            'despesas' => $despesas,
        ]);
    }
}
