<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pacientes')->insert([
            ['nome' => 'Maria Silva', 'data_nascimento' => '1990-01-10', 'cpf' => '123.456.789-00', 'telefone' => '21999990001', 'email' => 'maria@gmail.com', 'endereco' => 'Rua A, 100'],
            ['nome' => 'João Souza', 'data_nascimento' => '1985-05-20', 'cpf' => '987.654.321-00', 'telefone' => '21999990002', 'email' => 'joao@gmail.com', 'endereco' => 'Rua B, 200'],
            ['nome' => 'Ana Pereira', 'data_nascimento' => '1992-03-15', 'cpf' => '456.789.123-00', 'telefone' => '21999990003', 'email' => 'ana@gmail.com', 'endereco' => 'Rua C, 300'],
        ]);
    }
}
