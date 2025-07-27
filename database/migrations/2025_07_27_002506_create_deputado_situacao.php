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
        Schema::create('deputados_situacoes', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 10)->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('cod', 50)->nullable();
            $table->timestamps();

            $table->index('sigla');
            $table->index('cod');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados_situacoes');
    }
};
