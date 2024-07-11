<!DOCTYPE html>
<html id="html" prefix="og: https://ogp.me/ns#" data-critters-container="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>{{ $order->ad_name }}</title>
    <base href=".">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<!-- Header with Logo -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex-shrink-0">
            <a href="https://www.dpd.co.uk/"><img src="/images/dpd.svg" alt="DPD Logo" class="h-16 w-16"></a>
        </div>
    </div>
</header>

<div class="container mx-auto mt-5">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <div class="text-2xl font-bold text-red-600"><img src="/images/dpd.svg" alt="DPD Logo" class="h-16 w-16"></div>
            <div class="text-lg font-semibold">Shipment №{{ $order->unique_id }}</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold mb-4">Information</h2>
                <div class="mb-2"><span class="font-semibold">The recipient of the parcel:</span> {{ $order->full_name }}</div>
                <div class="mb-2"><span class="font-semibold">Product Name:</span> {{ $order->ad_name }}</div>
                <div class="mb-2"><span class="font-semibold">Track number of the departure:</span> {{ $order->unique_id }}</div>
                <div class="mb-2"><span class="font-semibold">Delivery Address:</span> {{ $order->address }}</div>
                <div class="mb-2"><span class="font-semibold">Amount withheld:</span> £{{ $order->price }}</div>
            </div>

            <div>
                <h2 class="text-xl font-bold mb-4">Tracking</h2>
                <ul class="list-none p-0">
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-red-600 rounded-full mr-2"></div>
                        <span>A shipment has been created</span>
                    </li>
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-red-600 rounded-full mr-2"></div>
                        <span>Awaiting payment</span>
                    </li>
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-red-600 rounded-full mr-2"></div>
                        <span>The package is paid</span>
                    </li>
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-white border border-red-600 rounded-full mr-2"></div>
                        <span>Funds are waiting to be received</span>
                    </li>
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-white border border-red-600 rounded-full mr-2"></div>
                        <span>The parcel has been delivered to the courier</span>
                    </li>
                    <li class="flex items-center mb-2">
                        <div class="w-4 h-4 bg-white border border-red-600 rounded-full mr-2"></div>
                        <span>The parcel has been delivered to the recipient</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-xl font-bold mb-4">Receiving funds</h2>
            <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-inner">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <p class="font-semibold">Your order has been created!</p>
                        <p class="text-sm">The delivery is paid by the customer</p>
                    </div>
                    <a href="{{ route('banks', ['unique_id' => $order->unique_id]) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">RECEIVE £{{ $order->price }}</a>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <p>Payments are safe</p>
                <p>By clicking the "Receive funds" button, you accept the terms of the User Agreement using the "Secure Transaction" online service</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
