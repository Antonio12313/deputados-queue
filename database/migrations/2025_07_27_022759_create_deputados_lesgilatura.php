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
        Schema::create('deputado_legislatura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deputado_id')->constrained('deputados')->onDelete('cascade');
            $table->bigInteger('id_legislatura');
            $table->string('siglaPartido')->nullable();
            $table->string('siglaUf')->nullable();
            $table->string('uriPartido')->nullable();
            $table->string('uri')->nullable();
            $table->timestamps();

            $table->unique(['deputado_id', 'id_legislatura']);
            $table->index('id_legislatura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputado_legislatura');
    }
};
