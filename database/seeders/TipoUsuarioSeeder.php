<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoUsuario;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $telas = [
            ['nome' => 'Administrador Master', 'slug' => 'admin-master', 'ativo' => '1'],
            ['nome' => 'Convidado', 'slug' => 'convidado', 'ativo' => '1'],
        ];

        foreach ($telas as $tela) {
            TipoUsuario::updateOrCreate([
                'nome' => $tela['nome'],
                'slug' => $tela['slug']
            ], $tela);
        }
    }
}