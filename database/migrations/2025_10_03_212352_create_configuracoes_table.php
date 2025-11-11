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
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_eleicao')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('cnpj', 18)->unique()->nullable();
            $table->boolean('suporte_0800')->default(false);
            $table->string('numero_suporte_0800')->nullable();
            $table->boolean('suporte_whatsapp')->default(false);
            $table->string('numero_suporte_whatsapp')->nullable();
            $table->dateTime('data_hora_inicio_eleicao')->nullable();
            $table->dateTime('data_hora_final_eleicao')->nullable();
            $table->string('remetente_do_email')->nullable();
            $table->string('assunto_do_email')->nullable();
            $table->text('mensagem_eleitor_email')->nullable();
            $table->text('mensagem_eleitor_sms', 170)->nullable();
            $table->boolean('menu_ajuda')->default(false);
            $table->boolean('menu_documentos')->default(false);
            $table->boolean('menu_trocar_senha')->default(false);
            $table->boolean('menu_recuperar_senha')->default(false);
            $table->boolean('autenticacao_de_2_etapas')->default(false);
            $table->boolean('trocar_de_senha_depois_login')->default(false);
            $table->boolean('dados_da_comissao')->default(false);
            $table->string('cor_principal')->nullable();
            $table->string('cor_hover')->nullable();
            $table->string('logotipo')->nullable();
            $table->string('caminho')->nullable();
            $table->text('termos', 5000)->nullable();
            $table->string('nome_presidente')->nullable();
            $table->string('cpf_presidente', 18)->nullable();
            $table->string('email_presidente')->nullable();
            $table->string('celular_presidente')->nullable();
            $table->string('nome_mebro_1')->nullable();
            $table->string('cpf_mebro_1', 18)->nullable();
            $table->string('email_mebro_1')->nullable();
            $table->string('celular_mebro_1')->nullable();
            $table->string('nome_mebro_2')->nullable();
            $table->string('cpf_mebro_2', 18)->nullable();
            $table->string('email_mebro_2')->nullable();
            $table->string('celular_mebro_2')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};