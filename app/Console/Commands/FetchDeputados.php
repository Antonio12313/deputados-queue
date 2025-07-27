<?php

namespace App\Console\Commands;

use App\Jobs\ProcessDeputados;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchDeputados extends Command
{
    protected $signature = 'deputados:fetch';
    protected $description = 'Busca todos os deputados da API da Câmara dos Deputados';

    public function handle(): void
    {
        $this->info('Iniciando busca de deputados...');

        $baseUrl = 'https://dadosabertos.camara.leg.br/api/v2/deputados';
        $params = [
            'dataInicio' => '1990-01-01'
        ];
        $paginaAtual = 1;
        $totalProcessado = 0;
        do {
            $this->info("Processando página {$paginaAtual}...");
            $response = Http::get($baseUrl, array_merge($params, ['pagina' => $paginaAtual]));

            if (!$response->successful()) {
                $this->error("Erro ao buscar página {$paginaAtual}: " . $response->status());
                break;
            }

            $data = $response->json();
            $deputados = $data['dados'] ?? [];
            $links = $data['links'] ?? [];

            foreach ($deputados as $deputadoData) {
                ProcessDeputados::dispatch($deputadoData);
                $totalProcessado++;
            }

            $this->warn("Página {$paginaAtual} processada. {$totalProcessado} deputados enviados para fila.");

            $proximaPagina = $this->getProximaPaginaUrl($links);
            if ($proximaPagina) {
                $paginaAtual++;
            } else {
                break;
            }
        } while ($proximaPagina);

        $this->info("Fim. Total de buscas realizadas: {$totalProcessado}");
    }

    private function getProximaPaginaUrl($links): ?string
    {
        foreach ($links as $link) {
            if ($link['rel'] === 'next') {
                return trim($link['href']);
            }
        }
        return null;
    }
}
