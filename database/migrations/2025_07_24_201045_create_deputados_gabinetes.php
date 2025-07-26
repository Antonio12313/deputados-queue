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
        Schema::create('deputados_gabinetes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deputado_id');
            $table->string('nome')->nullable();
            $table->string('predio')->nullable();
            $table->string('sala')->nullable();
            $table->string('andar')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->foreign('deputado_id')
                ->references('id_deputado')
                ->on('deputados');

            $table->index('deputado_id');
            $table->index('predio');
            $table->index('andar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados_gabinetes');
    }
};
