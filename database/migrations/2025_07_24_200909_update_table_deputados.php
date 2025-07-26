<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deputados', function (Blueprint $table) {
            $table->string('cpf')->nullable();
            $table->string('nome_civil')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->date('data_falecimento')->nullable();
            $table->string('sexo', 1)->nullable();
            $table->string('escolaridade')->nullable();
            $table->string('municipio_nascimento')->nullable();
            $table->string('uf_nascimento', 2)->nullable();
            $table->string('url_website')->nullable();

            $table->string('condicao_eleitoral')->nullable();
            $table->datetime('data_ultimo_status')->nullable();
            $table->string('descricao_status')->nullable();
            $table->string('nome_eleitoral')->nullable();
            $table->string('situacao')->nullable();
            $table->string('url_foto')->nullable();

            $table->boolean('detalhes_carregados')->default(false);
            $table->timestamp('detalhes_atualizados_em')->nullable();

            $table->index('cpf');
            $table->index('data_nascimento');
            $table->index('sexo');
            $table->index('uf_nascimento');
            $table->index('detalhes_carregados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deputados', function (Blueprint $table) {
            $table->dropIndex(['cpf']);
            $table->dropIndex(['data_nascimento']);
            $table->dropIndex(['sexo']);
            $table->dropIndex(['uf_nascimento']);
            $table->dropIndex(['detalhes_carregados']);

            $table->dropColumn([
                'cpf',
                'nome_civil',
                'data_nascimento',
                'data_falecimento',
                'sexo',
                'escolaridade',
                'municipio_nascimento',
                'uf_nascimento',
                'url_website',
                'condicao_eleitoral',
                'data_ultimo_status',
                'descricao_status',
                'nome_eleitoral',
                'situacao',
                'url_foto',
                'detalhes_carregados',
                'detalhes_atualizados_em'
            ]);
        });

    }
};
