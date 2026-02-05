<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdaTransactionController;

Route::post('/pda/borrow', [PdaTransactionController::class, 'borrow']);
Route::post('/pda/return', [PdaTransactionController::class, 'return']);
Route::get('/pda/status', [PdaTransactionController::class, 'status']);
Route::get('/pda/history', [PdaTransactionController::class, 'history']);
Route::get('/pda/dashboard', [PdaTransactionController::class, 'dashboard']);
