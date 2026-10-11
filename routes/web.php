<?php

use App\Http\Controllers\AvaliacaoPdfController;
use App\Http\Controllers\AvaliacaoPublicaController;
use App\Http\Controllers\AvaliadorExternoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModeloCsvController;
use Illuminate\Support\Facades\Route;

// ============================================================
// LOGIN UNIFICADO
// ============================================================
Route::get('/', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// AVALIAÇÃO PÚBLICA (servidor avaliado)
// ============================================================
Route::get('/avaliacao/{token}', [AvaliacaoPublicaController::class, 'show'])
    ->name('avaliacao.publica.show');
Route::post('/avaliacao/{token}/verificar', [AvaliacaoPublicaController::class, 'verificar'])
    ->name('avaliacao.publica.verificar');
Route::post('/avaliacao/{token}/assinar', [AvaliacaoPublicaController::class, 'assinar'])
    ->name('avaliacao.publica.assinar');
Route::post('/avaliacao/{token}/defesa', [AvaliacaoPublicaController::class, 'storeDefesa'])
    ->name('avaliacao.publica.defesa.store');
Route::post('/avaliacao/{token}/qualificacao', [AvaliacaoPublicaController::class, 'storeQualificacao'])
    ->name('avaliacao.publica.qualificacao.store');
Route::delete('/avaliacao/{token}/qualificacao/{qualificacao}', [AvaliacaoPublicaController::class, 'destroyQualificacao'])
    ->name('avaliacao.publica.qualificacao.destroy');

// ============================================================
// AVALIADOR EXTERNO (chefia sem login)
// ============================================================
Route::get('/avaliador-externo/lote/{token}', [AvaliadorExternoController::class, 'showLote'])
    ->name('avaliador.externo.lote.show');
Route::post('/avaliador-externo/lote/{token}/identificar', [AvaliadorExternoController::class, 'identificarLote'])
    ->name('avaliador.externo.lote.identificar');
Route::get('/avaliador-externo/lote/{token}/avaliacao/{avaliacao}', [AvaliadorExternoController::class, 'showAvaliacaoDoLote'])
    ->name('avaliador.externo.lote.avaliacao.show');
Route::post('/avaliador-externo/lote/{token}/avaliacao/{avaliacao}', [AvaliadorExternoController::class, 'salvarAvaliacaoDoLote'])
    ->name('avaliador.externo.lote.avaliacao.salvar');

Route::get('/avaliador-externo/{token}', [AvaliadorExternoController::class, 'show'])
    ->name('avaliador.externo.show');
Route::post('/avaliador-externo/{token}/salvar', [AvaliadorExternoController::class, 'salvar'])
    ->name('avaliador.externo.salvar');
Route::post('/avaliador-externo/{token}/assinar', [AvaliadorExternoController::class, 'assinar'])
    ->name('avaliador.externo.assinar');

// ============================================================
// PDF
// ============================================================
Route::get('/avaliacao/{avaliacao}/pdf', [AvaliacaoPdfController::class, 'download'])
    ->name('avaliacao.pdf.download');
Route::get('/avaliacao/{avaliacao}/pdf/view', [AvaliacaoPdfController::class, 'stream'])
    ->name('avaliacao.pdf.stream');

// ============================================================
// MODELO CSV
// ============================================================
Route::get('/admin/importar-servidores/modelo', [ModeloCsvController::class, 'servidores'])
    ->name('modelo.servidores');
