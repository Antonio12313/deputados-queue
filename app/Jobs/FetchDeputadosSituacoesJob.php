<?php

namespace App\Jobs;

use App\Models\DeputadoSituacao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDeputadosSituacoesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [1, 5, 10];

    public function handle()
    {
        try {
            $urls = [
                "https://dadosabertos.camara.leg.br/api/v2/referencias/deputados/codSituacao",
                "https://dadosabertos.camara.leg.br/api/v2/referencias/situacoesDeputado"
            ];

            $data = null;

            foreach ($urls as $url) {
                $response = Http::timeout(30)->get($url);

                if ($response->successful()) {
                    $responseData = $response->json();
                    if (isset($responseData['dados']) && !empty($responseData['dados'])) {
                        $data = $responseData;
                        Log::info("Dados obtidos com sucesso da URL: " . $url);
                        break;
                    }
                }
            }

            if (!$data) {
                Log::error("Falha ao buscar situações de deputados de ambas URLs");
                return;
            }

            $this->processSituacoes($data['dados']);

            Log::info("Situações de deputados atualizadas com sucesso", [
                'total' => count($data['dados'])
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao processar situações de deputados", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function processSituacoes(array $situacoes): void
    {
        foreach ($situacoes as $situacao) {
            try {
                if (!empty($situacao['sigla'])) {
                    DeputadoSituacao::updateOrCreate(
                        [
                            'sigla' => $situacao['sigla'],
                        ],
                        [
                            'cod' => $situacao['cod'] ?? null,
                            'nome' => $situacao['nome'] ?? null,
                            'descricao' => $situacao['descricao'] ?? null,
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error("Erro ao salvar situação", [
                    'situacao' => $situacao,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
