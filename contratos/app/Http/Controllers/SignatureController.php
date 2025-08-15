<?php

// app/Http/Controllers/SignatureController.php
namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\D4SignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function sendToD4Sign(Request $req, string $id, D4SignService $svc)
    {
        $user = $req->attributes->get('auth_user');
        if ($user->plan === 'free') return response()->json(['message' => 'Assinatura eletrônica é premium.'], 402);

        $payload = $req->validate([
            'signer.name'  => 'required|string',
            'signer.email' => 'required|email',
            'signer.cpf'   => 'nullable|string'
        ]);

        $contract = Contract::where('user_id', $user->id)->findOrFail($id);
        if (!$contract->pdf_path || !Storage::exists($contract->pdf_path)) {
            return response()->json(['message' => 'PDF não encontrado'], 404);
        }

        $upload = $svc->uploadDocument($contract->pdf_path, "Contrato-{$contract->id}.pdf");
        $docUUID = $upload['uuid'] ?? null;

        if (!$docUUID) return response()->json(['message' => 'Falha ao criar documento no D4Sign'], 502);

        $signer = [
            'name'  => $payload['signer']['name'],
            'email' => $payload['signer']['email'],
            'cpf'   => $payload['signer']['cpf'] ?? null,
            'send_email' => true,
        ];
        $svc->createSigner($docUUID, $signer);

        $contract->update([
            'status' => 'signing',
            'signature_meta' => ['provider' => 'd4sign', 'document_uuid' => $docUUID]
        ]);

        return ['ok' => true, 'document_uuid' => $docUUID];
    }

    // Webhook (configure no D4Sign com secret próprio)
    public function webhook(Request $req)
    {
        // Valide assinatura do webhook se disponível (MVP: só registra)
        // Atualize status do contrato baseado no evento recebido
        return response()->json(['ok' => true]);
    }
}
