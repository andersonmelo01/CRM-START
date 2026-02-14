<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Consulta;
use App\Models\Emitente;
use Carbon\Carbon;

class PagamentoController extends Controller
{
    public function index()
    {
        // Se não escolher data, usa HOJE
        $data = $request->data ?? Carbon::today()->format('Y-m-d');

        $pagamentos = Pagamento::with('consulta.paciente')
            ->whereDate('data_pagamento', $data)
            ->orderBy('data_pagamento', 'desc')
            ->get();

        return view('pagamentos.index', compact('pagamentos', 'data'));
        //$pagamentos = Pagamento::with('consulta.paciente')->get();
        //return view('pagamentos.index', compact('pagamentos'));
    }

    public function create()
    {
        $consultas = Consulta::whereDoesntHave('pagamento')->get();
        return view('pagamentos.create', compact('consultas'));
    }

    public function store(Request $request)
    {
        Pagamento::create([
            'consulta_id' => $request->consulta_id,
            'valor' => $request->valor,
            'forma_pagamento' => $request->forma_pagamento,
            'status' => $request->status,
            'data_pagamento' => $request->status == 'pago' ? now() : null
        ]);

        return redirect()->route('pagamentos.index');
    }
    public function marcarComoPago(Pagamento $pagamento)
    {
        if ($pagamento->status === 'pago') {
            return back();
        }

        $pagamento->update([
            'status' => 'pago',
            'data_pagamento' => now()
        ]);

        return back()->with('success', 'Pagamento confirmado com sucesso');
    }
    public function receber(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'valor_pago' => 'required|numeric|min:0.01'
        ]);

        $novoValorPago = $pagamento->valor_pago + $request->valor_pago;

        $status = $novoValorPago >= $pagamento->valor
            ? 'pago'
            : 'pendente';

        // 👉 Gera número do recibo APENAS quando virar PAGO
        if ($status === 'pago' && !$pagamento->numero_recibo) {

            $ultimoNumero = Pagamento::whereNotNull('numero_recibo')
                ->max('numero_recibo');

            $proximoNumero = $ultimoNumero
                ? str_pad((int)$ultimoNumero + 1, 6, '0', STR_PAD_LEFT)
                : '000001';

            $pagamento->numero_recibo = $proximoNumero;
            $pagamento->data_pagamento = now();
        }

        $pagamento->valor_pago = $novoValorPago;
        $pagamento->status = $status;
        $pagamento->save();

        return back()->with('success', 'Pagamento registrado com sucesso');
    }

    public function recibo(Pagamento $pagamento)
    {
        $emitente = Emitente::first(); // ou find(), where(), etc.

        return view('pagamentos.recibo', compact(
            'pagamento',
            'emitente'
        ));
    }
}
