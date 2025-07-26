<?php

namespace App\Jobs;

use App\Models\Deputado;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessDeputados implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    public function __construct(readonly array $deputadoData)
    {}

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $deputadoFormatted = [
                'id_deputado' => $this->deputadoData['id'],
                'id_legislatura' => $this->deputadoData['idLegislatura'],
                'nome' => $this->deputadoData['nome'],
                'email' => $this->deputadoData['email'] ?? null,
                'siglaPartido' => $this->deputadoData['siglaPartido'],
                'siglaUf' => $this->deputadoData['siglaUf'],
                'uriPartido' => $this->deputadoData['uriPartido'],
                'uri' => $this->deputadoData['uri']
            ];

            Deputado::updateOrCreate(
                ['id_deputado' => $deputadoFormatted['id_deputado']],
                $deputadoFormatted
            );
        } catch (Exception $e) {
            Log::error("Erro ao processar deputado: " . $e->getMessage(), [
                'deputado_data' => $this->deputadoData,
                'error' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

}
