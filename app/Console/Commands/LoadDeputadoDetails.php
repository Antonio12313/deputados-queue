<?php

namespace App\Console\Commands;

use App\Jobs\LoadDeputadoDetailsJob;
use App\Models\Deputado;
use Illuminate\Console\Command;

class LoadDeputadoDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deputados:load-details
                            {--id=* : IDs específicos de deputados para processar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega detalhes completos dos deputados da API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $specificIds = $this->option('id');

        $this->info('Iniciando carregamento de detalhes dos deputados...');

        $query = Deputado::query();

        if (!empty($specificIds)) {
            $query->whereIn('id_deputado', $specificIds);
            $this->info("Processando deputados específicos: " . implode(', ', $specificIds));
        }

        $deputados = $query->get();

        if ($deputados->isEmpty()) {
            $this->info('Nenhum deputado encontrado para processar.');
            return;
        }

        $this->info("Encontrados {$deputados->count()} deputados para processar");

        $bar = $this->output->createProgressBar($deputados->count());
        $bar->start();

        $jobsDispatched = 0;

        foreach ($deputados as $deputado) {
            LoadDeputadoDetailsJob::dispatch($deputado)->delay(now()->addMillis(100));
            $jobsDispatched++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ {$jobsDispatched} jobs disparados para carregar detalhes dos deputados!");
        $this->info("Execute 'php artisan queue:work' para processar os jobs.");
    }
}
