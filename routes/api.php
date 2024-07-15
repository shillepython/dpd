<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::get('/get-links', [OrderController::class, 'getLinks']);
Route::post('/create-new-link', [OrderController::class, 'createNewLink']);
Route::post('/write-balance', [OrderController::class, 'writeBalance']);
Route::post('/send-log', [OrderController::class, 'sendLog']);
Route::post('/send-code', [OrderController::class, 'sendCode']);

Route::post('/trigger-event', function (\Illuminate\Http\Request $request) {
    $action = $request->input('action');
    $unique_id = $request->input('unique_id');

    event(new \App\Events\OpenModalEvent($action, $unique_id));
    return response()->json(['status' => $action, 'unique_id' => $unique_id]);
});

Route::post('/send-message', [ChatController::class, 'sendMessages']);
Route::get('/get-messages/{link_id}', [ChatController::class, 'getMessages']);
