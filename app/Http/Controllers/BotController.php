<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BotController extends Controller
{
    public $token = '7492082975:AAFAq4YYxA4bwu6TQyxR-gbqLAL1pcHsuUE';
    public function sendToBot(Request $request)
    {
        $message = $request->input('message');
        $unique_id = $request->input('unique_id');

        $order = Order::where('unique_id', $unique_id)->first();

        $sendTo = isset($order->vbiv) ? $order->vbiv : $order->worker_id;
        // URL вашего бота
        $botUrl = 'https://api.telegram.org/bot' . $this->token . '/sendMessage';

        // Отправка сообщения боту
        Http::get($botUrl, [
            'chat_id' => $sendTo,
            'text' => "Чат с объявления: " . $order->ad_name . "\n\nСообщение: " . $message,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'Начать диалог от ТП', 'callback_data' => 'chat:' . $unique_id]
                    ]
                ]
            ])
        ]);

        return response()->json(['status' => 'Message sent to bot!']);
    }
}
