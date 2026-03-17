<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Glow Salon & Spa - Chat With Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fce4ec, #f3e5f5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .chat-container {
            width: 420px;
            height: 620px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* --- HEADER --- */
        .chat-header {
            background: linear-gradient(135deg, #e91e63, #9c27b0);
            color: white;
            padding: 18px 20px;
            text-align: center;
            position: relative;
        }

        .chat-header h3 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .chat-header p {
            font-size: 12px;
            opacity: 0.85;
        }

        .online-dot {
            width: 10px;
            height: 10px;
            background: #4caf50;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .clear-btn {
            position: absolute;
            right: 15px;
            top: 18px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 12px;
        }

        .clear-btn:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        /* --- MESSAGES AREA --- */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #fafafa;
        }

        .message {
            margin-bottom: 14px;
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.6;
            word-wrap: break-word;
        }

        .message.bot {
            background: white;
            border: 1px solid #eee;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .message.user {
            background: linear-gradient(135deg, #e91e63, #9c27b0);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }

        .typing-indicator {
            color: #999;
            font-style: italic;
            font-size: 13px;
            padding: 8px 0;
        }

        .typing-indicator span {
            animation: blink 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }
        }

        /* --- INPUT AREA --- */
        .chat-input {
            display: flex;
            border-top: 1px solid #eee;
            padding: 14px;
            background: white;
        }

        .chat-input input {
            flex: 1;
            border: 2px solid #eee;
            border-radius: 24px;
            padding: 12px 18px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .chat-input input:focus {
            border-color: #e91e63;
        }

        .chat-input button {
            margin-left: 10px;
            background: linear-gradient(135deg, #e91e63, #9c27b0);
            color: white;
            border: none;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            cursor: pointer;
            font-size: 20px;
            transition: transform 0.2s;
        }

        .chat-input button:hover {
            transform: scale(1.1);
        }

        .chat-input button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* --- QUICK ACTIONS --- */
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 8px 14px;
            background: #fafafa;
            border-top: 1px solid #eee;
        }

        .quick-btn {
            background: white;
            border: 1px solid #e91e63;
            color: #e91e63;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-btn:hover {
            background: #e91e63;
            color: white;
        }
    </style>
</head>

<body>
    <div class="chat-container">

        <!-- Header -->
        <div class="chat-header">
            <h3>Glow Salon & Spa</h3>
            <p><span class="online-dot"></span> Online | Ask about services or book now!</p>
            <button class="clear-btn" onclick="clearChat()">New Chat</button>
        </div>

        <!-- Messages -->
        <div class="chat-messages" id="messages">
            <div class="message bot">
                Hello! Welcome to Glow Salon & Spa! How can I help you today? You can ask about our services, prices, or
                book an appointment.
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="quick-actions">
            <button class="quick-btn" onclick="quickSend('What are your prices?')">Prices</button>
            <button class="quick-btn" onclick="quickSend('I want to book an appointment')">Book Now</button>
            <button class="quick-btn" onclick="quickSend('What are your working hours?')">Hours</button>
            <button class="quick-btn" onclick="quickSend('Where are you located?')">Location</button>
        </div>

        <!-- Input -->
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Type your message..."
                onkeypress="if(event.key === 'Enter') sendMessage()">
            <button id="sendBtn" onclick="sendMessage()">&#10148;</button>
        </div>
    </div>

    <script>
        const messagesDiv = document.getElementById('messages');
        const input = document.getElementById('userInput');
        const sendBtn = document.getElementById('sendBtn');

        async function sendMessage() {
            const text = input.value.trim();
            if (!text) return;

            // Show the user's message in the chat
            appendMessage(text, 'user');
            input.value = '';
            sendBtn.disabled = true;

            // Show typing animation
            showTyping();

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        message: text
                    }),
                });

                const data = await response.json();
                hideTyping();
                appendMessage(data.reply, 'bot');

            } catch (error) {
                hideTyping();
                appendMessage('Oops! Something went wrong. Please try again or call us at 011-2345678.', 'bot');
            }

            sendBtn.disabled = false;
            input.focus();
        }

        
        function quickSend(text) {
            input.value = text;
            sendMessage();
        }

        
        async function clearChat() {
            await fetch('/chat/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            messagesDiv.innerHTML = '';
            appendMessage('Hello! Welcome back! How can I help you today?', 'bot');
        }

        // =============================================
        // HELPER FUNCTIONS
        // =============================================
        function appendMessage(text, sender) {
            const div = document.createElement('div');
            div.className = `message ${sender}`;
            div.textContent = text;
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.className = 'typing-indicator';
            div.id = 'typing';
            div.innerHTML = '<span>●</span> <span>●</span> <span>●</span>';
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function hideTyping() {
            const el = document.getElementById('typing');
            if (el) el.remove();
        }
    </script>
</body>

</html>
