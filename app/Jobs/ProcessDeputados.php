<?php

namespace App\Jobs;

use App\Models\Deputado;
use App\Models\DeputadoLegislatura;
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
            $idLegislatura = $this->deputadoData['idLegislatura'] ?? null;

            if ($idLegislatura === null) {
                Log::warning("Campo 'idLegislatura' ausente ou nulo para deputado", [
                    'deputado_id' => $this->deputadoData['id'] ?? 'N/A',
                    'deputado_data' => $this->deputadoData
                ]);
                $idLegislatura = 0;
            }

            $deputadoFormatted = [
                'id_deputado' => $this->deputadoData['id'] ?? null,
                'id_legislatura' => $idLegislatura,
                'nome' => $this->deputadoData['nome'] ?? 'Nome não informado',
                'email' => $this->deputadoData['email'] ?? null,
                'siglaPartido' => $this->deputadoData['siglaPartido'] ?? null,
                'siglaUf' => $this->deputadoData['siglaUf'] ?? null,
                'uriPartido' => $this->deputadoData['uriPartido'] ?? null,
                'uri' => $this->deputadoData['uri'] ?? null
            ];

            if (empty($deputadoFormatted['id_deputado'])) {
                Log::error("ID do deputado ausente", ['deputado_data' => $this->deputadoData]);
                return;
            }

            Deputado::updateOrCreate(
                ['id_deputado' => $deputadoFormatted['id_deputado']],
                $deputadoFormatted
            );

            Log::info("Deputado processado com sucesso", [
                'id_deputado' => $deputadoFormatted['id_deputado'],
                'nome' => $deputadoFormatted['nome']
            ]);

        } catch (Exception $e) {
            Log::error("Erro ao processar deputado: " . $e->getMessage(), [
                'deputado_data' => $this->deputadoData,
                'error' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}
