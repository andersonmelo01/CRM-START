<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $pacientes = Paciente::when($request->filled('search'), function ($query) use ($request) {
            $query->where('nome', 'like', '%' . $request->search . '%')
                ->orWhere('cpf', 'like', '%' . $request->search . '%');
        })
            ->orderBy('nome')
            ->paginate(10);

        return view('pacientes.index', compact('pacientes'));
    }


    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'cpf' => [
                'required',
                'string',
                'max:14',
                'unique:pacientes,cpf'
            ],
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'endereco' => 'nullable|string|max:255'
        ], [
            'cpf.unique' => 'Este CPF já está cadastrado.'
        ]);

        Paciente::create($request->all());
        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $paciente->update($request->all());
        return redirect()->route('pacientes.index');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index');
    }

    public function historico(Paciente $paciente)
    {
        $consultas = $paciente->consultas()
            ->with([
                'medico',
                'prontuario',
                'exames',
                'pagamento'
            ])
            ->orderByDesc('data')
            ->orderByDesc('hora')
            ->get();

        return view('pacientes.historico', compact('paciente', 'consultas'));
    }
}
