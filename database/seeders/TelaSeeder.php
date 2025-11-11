<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tela;

class TelaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $telas = [
            ['slug' => 'dashboard', 'nome' => 'Dashboard', 'ativo' => '1'],
            ['slug' => 'declaracaoDaEleicao', 'nome' => 'Declaração da Eleição', 'ativo' => '1'],
            ['slug' => 'eleitores', 'nome' => 'Eleitores', 'ativo' => '1'],
            ['slug' => 'menus', 'nome' => 'Menus', 'ativo' => '1'],
            ['slug' => 'ajuda', 'nome' => 'Ajuda', 'ativo' => '1'],
            ['slug' => 'documentos', 'nome' => 'Documentos', 'ativo' => '1'],
            ['slug' => 'perguntas', 'nome' => 'Perguntas', 'ativo' => '1'],
            ['slug' => 'relatorios', 'nome' => 'Relatórios', 'ativo' => '1'],
            ['slug' => 'dadosDaEleicao', 'nome' => 'Dados da Eleição', 'ativo' => '1'],
            ['slug' => 'eleitoresLogados', 'nome' => 'Eleitores Logados', 'ativo' => '1'],
            ['slug' => 'listaDeEleitores', 'nome' => 'Lista de Eleitores', 'ativo' => '1'],
            ['slug' => 'listaDeChamada', 'nome' => 'Lista de Chamada', 'ativo' => '1'],
            ['slug' => 'logsDoEleitor', 'nome' => 'Logs do Eleitor', 'ativo' => '1'],
            ['slug' => 'votantes', 'nome' => 'Votantes', 'ativo' => '1'],
            ['slug' => 'naoVotantes', 'nome' => 'Não Votantes', 'ativo' => '1'],
            ['slug' => 'zeresimaDeVotos', 'nome' => 'Zerésima de Votos', 'ativo' => '1'],
            ['slug' => 'configuracoes', 'nome' => 'Configurações', 'ativo' => '1'],
            ['slug' => 'logsDeErro', 'nome' => 'Logs de Erro', 'ativo' => '1'],
        ];

        foreach ($telas as $tela) {
            Tela::updateOrCreate(['slug' => $tela['slug']], $tela);
        }
    }
}
