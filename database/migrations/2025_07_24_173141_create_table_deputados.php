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
        Schema::create('deputados', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_deputado');
            $table->bigInteger('id_legislatura');
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('siglaPartido')->nullable();
            $table->string('siglaUf')->nullable();
            $table->string('uriPartido')->nullable();
            $table->string('uri')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('id_legislatura');
            $table->index('nome');
            $table->index('id_deputado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
