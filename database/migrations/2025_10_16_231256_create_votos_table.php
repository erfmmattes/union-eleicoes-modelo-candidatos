<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleitor_id')->constrained('eleitores')->onDelete('cascade');
            $table->text('voto');
            $table->timestamp('votado_em')->useCurrent();
            $table->string('etapa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votos');
    }
};