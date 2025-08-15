<?php

// app/Services/D4SignService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class D4SignService
{
    protected string $url;
    protected string $token;
    protected string $account;

    public function __construct()
    {
        $this->url    = rtrim(config('services.d4sign.url'), '/');
        $this->token  = config('services.d4sign.token');
        $this->account = config('services.d4sign.account');
    }

    public function uploadDocument(string $filePath, string $name): array
    {
        // MVP: envia arquivo e cria documento
        return Http::attach('file', file_get_contents(storage_path("app/{$filePath}")), $name)
            ->post("{$this->url}/documents?tokenAPI={$this->token}&accountId={$this->account}")
            ->json();
    }

    public function createSigner(string $documentUUID, array $signer): array
    {
        return Http::post("{$this->url}/documents/{$documentUUID}/participants?tokenAPI={$this->token}&accountId={$this->account}", [
            'signers' => [$signer], // name, email, cpf, etc.
        ])->json();
    }
}
