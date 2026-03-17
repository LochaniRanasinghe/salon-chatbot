# Glow Salon & Spa - AI Chatbot

A modern, intelligent chatbot application built with Laravel for **Glow Salon & Spa**. The chatbot enables customers to inquire about services, pricing, working hours, location, and book appointments seamlessly through a responsive web interface.

## 🎯 Project Overview

This is an automated customer service solution designed specifically for salon and spa businesses. The chatbot uses AI to handle common customer inquiries, reducing manual support burden while providing instant responses 24/7.

### Key Features

- **Real-time Chat Interface** — Beautiful, responsive UI with gradient styling
- **AI-Powered Responses** — Intelligent chatbot service for salon-related queries
- **Session Management** — Track conversation history per user
- **Quick Actions** — One-click buttons for common questions (Prices, Hours, Location, Booking)
- **Typing Indicators** — Animated typing feedback for better UX
- **Error Handling** — Graceful fallback with support contact info
- **CSRF Protection** — Secure POST requests with Laravel CSRF tokens

## 🛠️ Tech Stack

- **Backend:** Laravel 12.54.1 PHP
- **Frontend:** HTML5, CSS3, Vanilla JavaScript (Async/Await)
- **Database:** MySQL/SQLite (for chat history & user data)
- **Build Tool:** Vite (for asset compilation)
- **Testing:** PHPUnit

## 📁 Project Structure

```
salon-chatbot/
├── app/
│   ├── Http/Controllers/      # Chat controller for handling requests
│   ├── Models/               # User & chat models
│   └── Services/             # SalonChatbotService (AI logic)
├── resources/
│   ├── views/
│   │   └── chatbot.blade.php  # Main chat UI (with embedded JS)
│   ├── css/                   # Styling
│   └── js/                    # Frontend scripts
├── routes/
│   └── web.php               # Chat endpoints
├── database/
│   ├── migrations/           # Table schemas
│   ├── factories/            # Test data generators
│   └── seeders/              # Database seeds
└── config/                   # App configuration
```

## 🚀 Getting Started

### Installation

1. **Clone the repository**

    ```bash
    git clone <repo-url>
    cd salon-chatbot
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Set up environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure database** in `.env` and run migrations

    ```bash
    php artisan migrate
    ```

5. **Build assets**

    ```bash
    npm run build
    ```

6. **Start the server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` to access the chatbot.

## 🔌 API Endpoints

| Method | Endpoint      | Description                              |
| ------ | ------------- | ---------------------------------------- |
| `POST` | `/chat/send`  | Send a user message and get bot response |
| `POST` | `/chat/clear` | Clear chat history for current session   |

### Request Example (POST /chat/send)

```javascript
fetch("/chat/send", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
    },
    body: JSON.stringify({ message: "What are your prices?" }),
});
```

### Response Example

```json
{
    "reply": "Our salon offers prices ranging from $25-$150 depending on services..."
}
```

## 💻 How the Chat Works

1. **User sends message** → `sendMessage()` captures input
2. **Show typing animation** → Visual feedback while processing
3. **API request** → POST to `/chat/send` with CSRF token
4. **Backend processing** → `SalonChatbotService` generates AI response
5. **Display bot reply** → Message appended and auto-scrolled into view
6. **Error handling** → Displays fallback message with salon phone number

## 🎨 UI Components

### Message Bubbles

- **User messages:** Right-aligned, pink-purple gradient background
- **Bot messages:** Left-aligned, white with border

### Quick Action Buttons

Pre-set queries for:

- Prices
- Book Appointment
- Working Hours
- Location

### Features

- Auto-scroll to latest message
- Disabled send button while loading
- Animated typing indicator
- Clear chat button
- Online status indicator

## 📝 Code Highlights

### Async/Await Pattern

The frontend uses modern JavaScript async/await for clean, non-blocking API calls:

```javascript
async function sendMessage() {
    const response = await fetch('/chat/send', {...});
    const data = await response.json();
    appendMessage(data.reply, 'bot');
}
```

### Service Layer

Backend logic is encapsulated in `SalonChatbotService.php`:

- Processes user queries
- Generates contextual responses
- Manages conversation state

## 🔐 Security

- ✅ CSRF token validation on all POST requests
- ✅ Input sanitization via `textContent` (prevents XSS)
- ✅ Secure JSON responses from backend

## 📞 Support

For salon inquiries, customers can call: **011-2345678**

## 🤖 AI Model

This chatbot is powered by **Google Gemini API**, which provides intelligent, context-aware responses to customer inquiries. The integration allows for natural language understanding and generation, making conversations feel human-like and helpful.

## 📌 Sample Project

This is a **sample project** demonstrating how to build an AI-powered chatbot for service-based businesses using Laravel and Google Gemini. Feel free to fork, modify, and adapt it for your own salon, spa, or service business!

## 📄 License

This project is licensed under the MIT License.

---

**Built with ❤️ for Glow Salon & Spa**
