<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emitente;

class EmitenteController extends Controller
{
    public function edit()
    {
        $emitente = Emitente::first();
        return view('emitente.form', compact('emitente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'documento' => 'required'
        ]);

        Emitente::updateOrCreate(
            ['id' => $request->id],
            $request->all()
        );

        return back()->with('success', 'Emitente salvo com sucesso');
    }
}
