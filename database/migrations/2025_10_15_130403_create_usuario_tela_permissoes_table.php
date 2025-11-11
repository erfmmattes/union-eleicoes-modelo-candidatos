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
        Schema::create('usuario_tela_permissoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('tela_slug'); // 'usuarios', 'configuracoes', 'relatorios', etc.
            $table->boolean('criar')->default(false);
            $table->boolean('importar_eleitores')->default(false);
            $table->boolean('enviar_senha')->default(false);
            $table->boolean('ver')->default(false);
            $table->boolean('editar')->default(false);
            $table->boolean('deletar')->default(false);
            $table->timestamps();

            $table->unique(['usuario_id', 'tela_slug'], 'usuario_tela_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_tela_permissoes');
    }
};
