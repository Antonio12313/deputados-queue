<?php

namespace App\Http\Controllers\Home\Resource;

use App\Models\Deputado;
use Illuminate\Http\Resources\Json\JsonResource;

class DeputadoResource extends JsonResource
{

    public function toArray($request)
    {
        /** @var Deputado $deputado */
        $deputado = $this->resource;
        return [
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
        ];
    }
}
