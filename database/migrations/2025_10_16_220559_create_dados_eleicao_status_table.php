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
        Schema::create('dados_eleicao_status', function (Blueprint $table) {
            $table->id();
            $table->boolean('total_eleitores')->default(false);
            $table->boolean('senhas_geradas')->default(false);
            $table->boolean('emails_enviados')->default(false);
            $table->boolean('telefones')->default(false);
            $table->boolean('sms_enviados')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_eleicao_status');
    }
};
