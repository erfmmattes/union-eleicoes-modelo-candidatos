<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DadosEleicaoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dados_eleicao_status')->insert([
            'total_eleitores' => false,
            'senhas_geradas' => false,
            'emails_enviados' => false,
            'telefones' => false,
            'sms_enviados' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}