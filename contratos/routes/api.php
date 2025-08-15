<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\UsageController;

Route::get('/health', fn() => ['ok' => true]);

Route::middleware('supabase')->group(function () {
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{id}', [TemplateController::class, 'show']);

    Route::get('/themes', [ThemeController::class, 'index']);

    Route::post('/contracts/generate', [ContractController::class, 'generate']); // PDF
    Route::get('/contracts', [ContractController::class, 'list']);
    Route::get('/contracts/{id}', [ContractController::class, 'show']);
    Route::get('/contracts/{id}/download', [ContractController::class, 'download']);

    Route::post('/contracts/{id}/send-to-sign', [SignatureController::class, 'sendToD4Sign']); // premium
    Route::get('/usage', [UsageController::class, 'me']); // contagem mês atual
});

// Webhook do D4Sign (sem auth de usuário)
Route::post('/webhooks/d4sign', [SignatureController::class, 'webhook']);
