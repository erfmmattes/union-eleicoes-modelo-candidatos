<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UsuarioTelaPermissao;

class UsuarioTelaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuariosTelas = [
            ['usuario_id' => '1', 'tela_slug' => 'dashboard', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'declaracaoDaEleicao', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'eleitores', 'criar' => '1', 'importar_eleitores' => '1', 'enviar_senha' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'menus', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'ajuda', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'documentos', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'setores', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'candidatos', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'etapas', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'escolhas', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'relatorios', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'dadosDaEleicao', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'eleitoresLogados', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'listaDeEleitores', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'listaDeChamada', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'logsDoEleitor', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'votantes', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'naoVotantes', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'zeresimaDeVotos', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'configuracoes', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'logsDeErro', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
            ['usuario_id' => '1', 'tela_slug' => 'usuarios', 'criar' => '1', 'ver' => '1', 'editar' => '1', 'deletar' => '1'],
        ];

        foreach ($usuariosTelas as $usuarioTela) {
            UsuarioTelaPermissao::updateOrCreate([
                'usuario_id' => $usuarioTela['usuario_id'],
                'tela_slug' => $usuarioTela['tela_slug'],
                'criar' => $usuarioTela['criar'],
                'ver' => $usuarioTela['ver'],
                'editar' => $usuarioTela['editar'],
                'deletar' => $usuarioTela['deletar']
            ], $usuarioTela);
        }
    }
}