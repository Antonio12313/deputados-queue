<?php

namespace App\Console\Commands;

use App\Jobs\FetchDeputadoDespesasJob;
use App\Models\Deputado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchDeputadosDespesasCommand extends Command
{
    protected $signature = 'deputados:fetch-despesas';

    protected $description = 'Busca despesas dos deputados da API da Câmara';

    public function handle()
    {
        $deputados = Deputado::all();

        if ($deputados->isEmpty()) {
            $this->error('Nenhum deputado encontrado');
            return 1;
        }

        $this->info("Iniciando busca de despesas para {$deputados->count()} deputados");

        $bar = $this->output->createProgressBar($deputados->count());
        $bar->start();

        foreach ($deputados as $deputado) {
            try {
                FetchDeputadoDespesasJob::dispatch($deputado->id_deputado);
                $bar->advance();
            } catch (\Exception $e) {
                Log::error("Erro ao disparar job para deputado {$deputado->id_deputado}", [
                    'error' => $e->getMessage()
                ]);
                $this->error("Erro ao processar deputado {$deputado->id_deputado}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Processo concluído!');

        return 0;
    }
}

