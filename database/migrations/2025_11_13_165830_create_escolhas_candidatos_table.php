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
        Schema::create('escolhas_candidatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etapas_candidatos_id')->constrained('etapas_candidatos')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->string('nome')->nullable();
            $table->string('cargo')->nullable();
            $table->boolean('tem_foto')->default(false);
            $table->string('foto')->nullable();
            $table->string('caminho')->nullable();
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
        Schema::dropIfExists('escolhas_candidatos');
    }
};
