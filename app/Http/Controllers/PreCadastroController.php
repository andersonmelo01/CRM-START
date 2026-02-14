<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PreCadastroController extends Controller
{
    // 👉 MOSTRAR A TELA
    public function index()
    {
        return view('pre-cadastro');
    }

    // 👉 SALVAR O PRÉ-CADASTRO
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'cpf' => 'required|string|max:14',
            'data_nascimento' => 'required|date',
            'telefone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'endereco' => 'nullable|string'
        ]);

        // Remove máscara do CPF
        $cpf = preg_replace('/\D/', '', $request->cpf);

        // Verifica se já existe
        $paciente = Paciente::where('cpf', $cpf)->first();

        if ($paciente) {
            return back()->withErrors([
                'cpf' => 'Paciente já cadastrado. Faça apenas o agendamento.'
            ])->withInput();
        }

        Paciente::create([
            'nome' => $request->nome,
            'cpf' => $cpf,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'endereco' => $request->endereco,
        ]);

        return redirect()->route('agendamento.publico')
            ->with('success', 'Pré-cadastro realizado! Agora faça o agendamento.');
    }
}
