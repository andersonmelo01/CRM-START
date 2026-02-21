<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Emitente;
use Illuminate\Support\Facades\Auth;

class VerificarValidadeEmitente
{
    public function handle(Request $request, Closure $next)
    {
        // 🚫 Evita loop infinito nas rotas de autenticação
        if ($request->is('login') || $request->is('logout') || $request->is('password/*')) {
            return $next($request);
        }

        $emitente = Emitente::first();

        if (!$emitente) {
            return $next($request);
        }

        $hoje = Carbon::today();
        $validade = Carbon::parse($emitente->validade);

        // Dias restantes (inteiro)
        //$diasRestantes = (int) $hoje->diffInDays($validade, false);
        $diasRestantes = (int) Carbon::today()->diffInDays(Carbon::parse($emitente->validade), false);


        // 🔴 Se venceu ou passou até 5 dias → inativar e bloquear
        if ($diasRestantes <= 0 && $diasRestantes >= -5) {
            $emitente->ativo = false;
            $emitente->save();

            Auth::logout();

            return redirect('/login')->with(
                'erro_validade',
                'Sistema expirado! Entre em contato com o suporte.'
            );
        }

        // 🔴 Se passou mais de 5 dias → bloqueio total
        if ($diasRestantes < -5) {
            Auth::logout();

            return redirect('/login')->with(
                'erro_validade',
                'Sistema bloqueado por expiração da licença.'
            );
        }

        // 🟡 Avisar quando faltar 3 dias
        if ($diasRestantes <= 3 && $diasRestantes > 0) {
            session()->flash(
                'aviso_validade',
                "Atenção! Sua licença vence em {$diasRestantes} dia(s)."
            );
        }

        return $next($request);
    }
}
