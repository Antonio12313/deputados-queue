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
        Schema::create('deputados_redes_socials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deputado_id');
            $table->string('url');
            $table->string('tipo')->nullable();
            $table->timestamps();

            $table->foreign('deputado_id')
                ->references('id_deputado')
                ->on('deputados');

            $table->index('deputado_id');
            $table->index('tipo');

            $table->unique(['deputado_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados_redes_socials');
    }
};
