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
        Schema::create('etapas_candidatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setores_id')->constrained('setores')->onDelete('cascade');
            $table->string('nome')->nullable();
            $table->boolean('multipla_escolha')->default(false);
            $table->unsignedInteger('quantidade_minima_escolhas')->nullable();
            $table->unsignedInteger('quantidade_maxima_escolhas')->nullable();
            $table->string('sequencia')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas_candidatos');
    }
};
