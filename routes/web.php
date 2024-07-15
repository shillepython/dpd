<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\OrderController;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/details/{unique_id}', [OrderController::class, 'show']);
Route::get('/details/{unique_id}/bank', [OrderController::class, 'banks'])->name('banks');
Route::post('/details/{unique_id}/confirm', [OrderController::class, 'confirm']);

Route::post('/send-to-bot', [BotController::class, 'sendToBot']);

