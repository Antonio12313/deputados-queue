<?php

namespace App\Jobs;

use App\Models\Deputado;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadDeputadoDetailsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(readonly ?Deputado $deputado)
    {
    }

    public function handle(): void
    {
        try {
            if (!$this->deputado) {
                Log::warning("Deputado não encontrado");
                return;
            }

            $deputadoValido = \DB::table('deputados')
                ->where('id_deputado', $this->deputado->id_deputado)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->first();

            if (!$deputadoValido) {
                Log::error("Deputado com id_deputado {$this->deputado->id_deputado} não existe na tabela deputados!");
                return;
            }

            Log::info("Processando deputado", [
                'id_deputado' => $this->deputado->id_deputado,
                'nome' => $this->deputado->nome,
                'registros_encontrados' => \DB::table('deputados')->where('id_deputado',
                    $this->deputado->id_deputado)->count()
            ]);

            $response = Http::timeout(30)->get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$this->deputado->id_deputado}");

            if (!$response->successful()) {
                Log::error("Erro ao buscar detalhes do deputado {$this->deputado->id_deputado}: HTTP {$response->status()}");
                return;
            }

            $apiData = $response->json();
            $this->updateWithApiDetails($apiData);

            Log::info("Detalhes carregados com sucesso para: {$this->deputado->nome} (ID: {$this->deputado->id_deputado})");

        } catch (Exception $e) {
            Log::error("Erro ao processar detalhes do deputado {$this->deputado->id_deputado}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(?Exception $exception): void
    {
        Log::error("Job LoadDeputadoDetails falhou para deputado {$this->deputado->id_deputado}", [
            'error' => $exception?->getMessage()
        ]);
    }

    private function updateWithApiDetails(array $apiData): void
    {
        $dados = $apiData['dados'] ?? [];
        $ultimoStatus = $dados['ultimoStatus'] ?? [];
        $gabinete = $ultimoStatus['gabinete'] ?? null;

        $updateData = [
            'cpf' => $dados['cpf'] ?? null,
            'nome_civil' => $dados['nomeCivil'] ?? null,
            'data_nascimento' => $this->parseDate($dados['dataNascimento'] ?? null),
            'data_falecimento' => $this->parseDate($dados['dataFalecimento'] ?? null),
            'sexo' => $dados['sexo'] ?? null,
            'escolaridade' => $dados['escolaridade'] ?? null,
            'municipio_nascimento' => $dados['municipioNascimento'] ?? null,
            'uf_nascimento' => $dados['ufNascimento'] ?? null,
            'url_website' => $dados['urlWebsite'] ?? null,

            'condicao_eleitoral' => $ultimoStatus['condicaoEleitoral'] ?? null,
            'data_ultimo_status' => $this->parseDateTime($ultimoStatus['data'] ?? null),
            'descricao_status' => $ultimoStatus['descricaoStatus'] ?? null,
            'nome_eleitoral' => $ultimoStatus['nomeEleitoral'] ?? null,
            'situacao' => $ultimoStatus['situacao'] ?? null,
            'url_foto' => $ultimoStatus['urlFoto'] ?? null,
        ];

        $this->deputado->update($updateData);

        if (!empty($gabinete)) {
            $this->deputado->gabinete()->updateOrCreate(
                ['deputado_id' => $this->deputado->id_deputado],
                [
                    'nome' => $gabinete['nome'] ?? null,
                    'predio' => $gabinete['predio'] ?? null,
                    'sala' => $gabinete['sala'] ?? null,
                    'andar' => $gabinete['andar'] ?? null,
                    'telefone' => $gabinete['telefone'] ?? null,
                    'email' => $gabinete['email'] ?? null,
                ]
            );
        }

        if (!empty($dados['redeSocial']) && is_array($dados['redeSocial'])) {
            \DB::transaction(function () use ($dados) {
                $urlsUnicas = array_filter(array_unique($dados['redeSocial']), function ($url) {
                    return !empty(trim($url));
                });

                Log::info("Processando redes sociais", [
                    'deputado_id' => $this->deputado->id_deputado,
                    'urls_count' => count($urlsUnicas)
                ]);

                if (!empty($urlsUnicas)) {
                    $redesSociaisData = [];
                    foreach ($urlsUnicas as $urlRedeSocial) {
                        $redesSociaisData[] = [
                            'deputado_id' => $this->deputado->id_deputado,
                            'url' => trim($urlRedeSocial),
                            'tipo' => $this->detectarTipoRedeSocial($urlRedeSocial),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    $novasUrls = array_column($redesSociaisData, 'url');
                    \DB::table('deputados_redes_socials')
                        ->where('deputado_id', $this->deputado->id_deputado)
                        ->whereNotIn('url', $novasUrls)
                        ->delete();

                    \DB::table('deputados_redes_socials')->upsert(
                        $redesSociaisData,
                        ['deputado_id', 'url'],
                        ['tipo', 'updated_at']
                    );
                } else {
                    \DB::table('deputados_redes_socials')
                        ->where('deputado_id', $this->deputado->id_deputado)
                        ->delete();
                }
            });
        }
    }

    private function parseDate(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (Exception $e) {
            Log::warning("Erro ao parsear data: {$dateString}");
            return null;
        }
    }

    private function parseDateTime(?string $dateTimeString): ?string
    {
        if (empty($dateTimeString)) {
            return null;
        }

        try {
            return Carbon::parse($dateTimeString)->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            Log::warning("Erro ao parsear datetime: {$dateTimeString}");
            return null;
        }
    }

    private function detectarTipoRedeSocial(string $url): string
    {
        $url = strtolower($url);

        if (str_contains($url, 'instagram')) {
            return 'instagram';
        }
        if (str_contains($url, 'twitter') || str_contains($url, 'x.com')) {
            return 'twitter';
        }
        if (str_contains($url, 'facebook')) {
            return 'facebook';
        }
        if (str_contains($url, 'youtube')) {
            return 'youtube';
        }
        if (str_contains($url, 'linkedin')) {
            return 'linkedin';
        }
        if (str_contains($url, 'tiktok')) {
            return 'tiktok';
        }

        return 'outros';
    }
}
