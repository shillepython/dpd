<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ChatController extends Controller
{
    public function getMessages($unique_id): JsonResponse
    {
        $messages = Message::where('unique_id', $unique_id)->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    public function sendMessages(Request $request): JsonResponse
    {
        $message = $request->input('message');
        $unique_id = $request->input('unique_id');
        $who = $request->input('who');

        $message = \App\Models\Message::create([
            'unique_id' => $unique_id,
            'message' => $message,
            'who' => $who
        ]);

        if ($who === 'worker') {
            event(new \App\Events\MessageSent($message));
        }

        return response()->json(['status' => 'Message Sent!'], 200);
    }
}
