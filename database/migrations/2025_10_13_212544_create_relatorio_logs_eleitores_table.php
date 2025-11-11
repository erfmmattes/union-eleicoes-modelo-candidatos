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
        Schema::create('relatorio_logs_eleitores', function (Blueprint $table) {
            $table->id();
            $table->string('eleitor_id')->nullable();
            $table->string('eleitor_nome')->nullable();
            $table->string('acao', 100)->nullable();
            $table->text('mensagem');
            $table->string('ip', 45)->nullable();
            $table->string('pagina')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorio_logs_eleitores');
    }
};