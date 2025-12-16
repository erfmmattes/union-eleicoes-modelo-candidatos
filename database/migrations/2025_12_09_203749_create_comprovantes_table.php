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
        Schema::create('comprovantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleitor_id')->constrained('eleitores')->onDelete('cascade');
            $table->foreignId('etapa_id')->constrained('etapas_candidatos')->onDelete('cascade');
            $table->string('nome_eleitor');    
            $table->string('nome_votacao');       
            $table->string('cpf_cnpj', 18);            
            $table->string('ip', 45)->nullable(); 
            $table->timestamp('data_hora');       
            $table->string('chave_autenticacao')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprovantes');
    }
};
