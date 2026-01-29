<?php

namespace App\Http\Controllers;

use App\Models\Bloqueio;
use Illuminate\Http\Request;

use App\Models\Medico;

use Carbon\Carbon;


class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->busca;

        $medicos = Medico::when($busca, function ($q) use ($busca) {
            $q->where('nome', 'like', "%$busca%")
                ->orWhere('crm', 'like', "%$busca%")
                ->orWhere('especialidade', 'like', "%$busca%");
        })
            ->orderBy('nome')
            ->paginate(10);

        return view('medicos.index', compact('medicos')); 
    }

    public function create()
    {
        return view('medicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'crm' => 'required|unique:medicos',
            'especialidade' => 'required'
        ]);

        Medico::create($request->all());

        return redirect()->route('medicos.index')
            ->with('success', 'Médico cadastrado com sucesso!');
    }

    public function edit(Medico $medico)
    {
        return view('medicos.edit', compact('medico'));
    }

    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'nome' => 'required',
            'crm' => 'required|unique:medicos,crm,' . $medico->id,
            'especialidade' => 'required'
        ]);

        $medico->update($request->all());

        return redirect()->route('medicos.index')
            ->with('success', 'Médico atualizado!');
    }

    public function destroy(Medico $medico)
    {
        $medico->delete();

        return redirect()->route('medicos.index')
            ->with('success', 'Médico removido!');
    }
   
    public function bloqueios(Medico $medico)
    {
        $bloqueios = $medico->bloqueios()
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get();

        return view('medicos.bloqueios', compact('medico', 'bloqueios'));
    }
    public function statusBloqueio($medicoId)
    {
        $hoje  = now()->format('Y-m-d');
        $agora = now()->format('H:i:s');

        $bloqueado = Bloqueio::where('medico_id', $medicoId)
            ->where('data', $hoje)
            ->where(function ($q) use ($agora) {
                $q->whereNull('hora_inicio')
                    ->orWhere(function ($q) use ($agora) {
                        $q->where('hora_inicio', '<=', $agora)
                            ->where('hora_fim', '>=', $agora);
                    });
            })
            ->exists();

        return response()->json([
            'bloqueado' => $bloqueado
        ]);
    }
}
