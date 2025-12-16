<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;

class ConfiguracaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuracao::updateOrCreate([
            'nome_eleicao' => 'Unir Votações',
            'razao_social' => 'Unir Votações LTDA',
            'cnpj' => '01002003000405',
            'suporte_0800' => true,
            'numero_suporte_0800' => '0800 000 0000',
            'suporte_whatsapp' => true,
            'numero_suporte_whatsapp' => '51999999999',
            'data_hora_inicio_eleicao' => now()->addDays(7),
            'data_hora_final_eleicao' => now()->addDays(8),
            'remetente_do_email' => 'Unir Votações - <no-reply@unirvotacoes.com.br>',
            'assunto_do_email' => 'Eleição de Cipa 2025 - 05/11/2025',
            'mensagem_eleitor_email' => 'mensagem de e-mail',
            'mensagem_eleitor_sms' => 'mensagem de sms',
            'menu_ajuda' => true,
            'menu_documentos' => true,
            'menu_trocar_senha' => true,
            'menu_recuperar_senha' => true,
            'autenticacao_de_2_etapas' => false,
            'trocar_de_senha_depois_login' => false,
            'dados_da_comissao' => false,
            'cor_principal' => '#3498db',
            'cor_hover' => '#2980b9',
            'logotipo' => null,
            'caminho' => null,
            'termos' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'nome_presidente' => 'teste presidente',
            'cpf_presidente' => '00000000000',
            'email_presidente' => 'testePresidente@teste.com',
            'celular_presidente' => '00000000000',
            'nome_mebro_1' => 'teste membro 1',
            'cpf_mebro_1' => '00000000000',
            'email_mebro_1' => 'testeMembro1@teste.com',
            'celular_mebro_1' => '00000000000',
            'nome_mebro_2' => 'teste membro 2',
            'cpf_mebro_2' => '00000000000',
            'email_mebro_2' => 'testeMembro2@teste.com',
            'celular_mebro_2' => '00000000000',
        ]);
    }
}