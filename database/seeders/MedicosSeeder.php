<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medicos')->insert([
            [
                'nome' => 'Dr. Carlos',
                'especialidade' => 'Cardiologia',
                'email' => 'carlos@clinica.com',
                'crm' => '12345' // Preencher o CRM
            ],
            [
                'nome' => 'Dra. Fernanda',
                'especialidade' => 'Pediatria',
                'email' => 'fernanda@clinica.com',
                'crm' => '67890' // Preencher o CRM
            ],
        ]);
    }
}
