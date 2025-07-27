<?php

namespace App\Console\Commands;

use App\Jobs\FetchDeputadosSituacoesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchDeputadosSituacoesCommand extends Command
{
    protected $signature = 'deputados:fetch-situacoes';

    protected $description = 'Busca as situações possíveis dos deputados da API da Câmara';

    public function handle()
    {
        try {
            $this->info('Iniciando busca de situações de deputados...');

            FetchDeputadosSituacoesJob::dispatch();

            $this->info('Job de situações de deputados disparado com sucesso!');

            return 0;
        } catch (\Exception $e) {
            Log::error("Erro ao disparar job de situações de deputados", [
                'error' => $e->getMessage()
            ]);

            $this->error('Erro ao processar: ' . $e->getMessage());
            return 1;
        }
    }
}
