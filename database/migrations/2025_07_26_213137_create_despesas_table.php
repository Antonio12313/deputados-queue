<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('deputados_despesas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deputado_id');
            $table->integer('ano');
            $table->string('cnpj_cpf_fornecedor', 14)->nullable();
            $table->bigInteger('cod_documento')->nullable();
            $table->bigInteger('cod_lote')->nullable();
            $table->integer('cod_tipo_documento')->nullable();
            $table->date('data_documento')->nullable();
            $table->integer('mes');
            $table->string('nome_fornecedor')->nullable();
            $table->string('num_documento')->nullable();
            $table->string('num_ressarcimento')->nullable();
            $table->integer('parcela')->nullable();
            $table->string('tipo_despesa')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->text('url_documento')->nullable();
            $table->decimal('valor_documento', 15, 2)->nullable();
            $table->decimal('valor_glosa', 15, 2)->nullable();
            $table->decimal('valor_liquido', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('deputado_id')
                ->references('id_deputado')
                ->on('deputados')
                ->onDelete('cascade');

            $table->index(['deputado_id', 'ano', 'mes']);
            $table->index('cnpj_cpf_fornecedor');
            $table->index('data_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('deputados_despesas');
    }
};
