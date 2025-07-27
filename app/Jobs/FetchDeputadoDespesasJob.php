<?php

namespace App\Jobs;

use App\Models\DeputadoDespesa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDeputadoDespesasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $backoff = [1, 5, 10];

    public function __construct(
        protected int $deputadoId,
        protected ?int $pagina = 1,
        protected int $itens = 100
    ) {
    }

    public function handle()
    {
        try {
            $url = "https://dadosabertos.camara.leg.br/api/v2/deputados/{$this->deputadoId}/despesas";

            $params = [
                'pagina' => $this->pagina,
                'itens' => $this->itens,
            ];

            $response = Http::timeout(30)->get($url, $params);

            if ($response->failed()) {
                Log::error("Falha ao buscar despesas do deputado {$this->deputadoId}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return;
            }

            $data = $response->json();

            if (!isset($data['dados']) || empty($data['dados'])) {
                Log::info("Nenhuma despesa encontrada para o deputado {$this->deputadoId}");
                return;
            }

            $this->processDespesas($data['dados']);

            if (isset($data['links'])) {
                $nextPage = $this->getNextPage($data['links']);
                if ($nextPage && $nextPage > $this->pagina) {
                    self::dispatch($this->deputadoId, $nextPage, $this->itens)
                        ->delay(now()->addSeconds(2));
                }
            }

        } catch (\Exception $e) {
            Log::error("Erro ao processar despesas do deputado {$this->deputadoId}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function processDespesas(array $despesas): void
    {
        foreach ($despesas as $despesa) {
            try {
                DeputadoDespesa::updateOrCreate(
                    [
                        'deputado_id' => $this->deputadoId,
                        'cod_documento' => $despesa['codDocumento'] ?? null,
                        'cod_lote' => $despesa['codLote'] ?? null,
                        'data_documento' => isset($despesa['dataDocumento']) ?
                            date('Y-m-d', strtotime($despesa['dataDocumento'])) : null,
                    ],
                    [
                        'ano' => $despesa['ano'] ?? null,
                        'cnpj_cpf_fornecedor' => $despesa['cnpjCpfFornecedor'] ?? null,
                        'cod_tipo_documento' => $despesa['codTipoDocumento'] ?? null,
                        'mes' => $despesa['mes'] ?? null,
                        'nome_fornecedor' => $despesa['nomeFornecedor'] ?? null,
                        'num_documento' => $despesa['numDocumento'] ?? null,
                        'num_ressarcimento' => $despesa['numRessarcimento'] ?? null,
                        'parcela' => $despesa['parcela'] ?? null,
                        'tipo_despesa' => $despesa['tipoDespesa'] ?? null,
                        'tipo_documento' => $despesa['tipoDocumento'] ?? null,
                        'url_documento' => $despesa['urlDocumento'] ?? null,
                        'valor_documento' => $despesa['valorDocumento'] ?? null,
                        'valor_glosa' => $despesa['valorGlosa'] ?? null,
                        'valor_liquido' => $despesa['valorLiquido'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Erro ao salvar despesa", [
                    'deputado_id' => $this->deputadoId,
                    'despesa' => $despesa,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function getNextPage(array $links): ?int
    {
        foreach ($links as $link) {
            if ($link['rel'] === 'next' && isset($link['href'])) {
                parse_str(parse_url($link['href'], PHP_URL_QUERY), $params);
                return $params['pagina'] ?? null;
            }
        }
        return null;
    }
}
