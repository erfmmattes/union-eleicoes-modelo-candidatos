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
        Schema::create('eleitores', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('campo_opcional')->nullable();
            $table->string('celular')->nullable();
            $table->string('setor')->nullable();
            $table->string('peso_do_voto')->nullable();
            $table->string('email')->unique();
            $table->string('cpf_cnpj', 18)->unique();
            $table->string('senha')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->boolean('votou')->default(false);
            $table->boolean('status')->default(false);
            $table->boolean('enviou_senha_email')->default(false);
            $table->boolean('enviou_senha_sms')->default(false);
            $table->boolean('passou_por_ajuste')->default(false);
            $table->boolean('aceitou_os_termos')->default(false);
            $table->string('nome_do_representante')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleitores');
    }
};