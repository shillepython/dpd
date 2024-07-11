<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/details/{unique_id}', [OrderController::class, 'show']);
Route::get('/details/{unique_id}/bank', [OrderController::class, 'banks'])->name('banks');
Route::post('/details/{unique_id}/confirm', [OrderController::class, 'confirm']);
