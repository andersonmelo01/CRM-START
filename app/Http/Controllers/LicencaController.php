<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Emitente;

class LicencaController extends Controller
{
    public function validarOffline(Request $request)
    {
        $request->validate([
            'chave_licenca' => 'required'
        ]);

        $emitente = Emitente::first();

        if (!$emitente) {
            return back()->with('erro_validade', 'Emitente não encontrado.');
        }

        $resultado = $this->validarChave($request->chave_licenca, $emitente);

        if ($resultado === true) {
            return back()->with('success', 'Licença renovada com sucesso!');
        }

        return back()->with('erro_validade', $resultado);
    }


    private function validarChave($chave, $emitente)
    {
        $secret = 'AMS_SECRET_2026';

        $decoded = base64_decode($chave);

        if (!$decoded || !str_contains($decoded, '|')) {
            return 'Chave inválida ou corrompida.';
        }

        [$json, $assinatura] = explode('|', $decoded);

        // valida assinatura
        $assinaturaValida = hash_hmac('sha256', $json, $secret);
        if (!hash_equals($assinaturaValida, $assinatura)) {
            return 'Assinatura inválida.';
        }

        $dados = json_decode($json, true);

        if (!$dados || !isset($dados['documento'], $dados['validade'])) {
            return 'Licença mal formada.';
        }

        // remove máscara do documento do banco
        $docSistema = preg_replace('/\D/', '', $emitente->documento);

        if ($dados['documento'] !== $docSistema) {
            return 'Licença não pertence a este CPF/CNPJ.';
        }

        // 🔥 ATUALIZA A LICENÇA
        $emitente->ativo = true;
        $emitente->validade = $dados['validade'];
        $emitente->save();

        return true;
    }
}
