<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'name' => 'Éric',
            'email' => 'vagas.edigital@gmail.com',
            'tipo_usuario' => 'admin-master',
            'password' => '18951770',
            'status' => '1',
            'trocar_senha' => '1',
            'conta_ativa' => '1'
        ]);
    }
}
