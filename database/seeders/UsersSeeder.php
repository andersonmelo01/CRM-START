<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrador',
                'email' => 'admin@crm.com',
                'password' => bcrypt('123456'),
                'perfil' => 'admin'
            ],
            [
                'name' => 'Recepcionista',
                'email' => 'recepcao@crm.com',
                'password' => bcrypt('123456'),
                'perfil' => 'secretaria'
            ],
            [
                'name' => 'Médico',
                'email' => 'medico@crm.com',
                'password' => bcrypt('123456'),
                'perfil' => 'medico'
            ],
        ]);
    }
}
