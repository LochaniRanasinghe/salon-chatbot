<?php

namespace App\Http\Controllers;

use App\Services\SalonChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Show the chat page
     */
    public function index()
    {
        return view('chatbot');
    }

    /**
     * Handle incoming chat messages
     */
    public function send(Request $request, SalonChatbotService $chatbot)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $history = session('chat_history', []);

        $history[] = [
            'role'    => 'user',
            'content' => $request->message,
        ];

        $reply = $chatbot->chat($history);

        $history[] = [
            'role'    => 'assistant',
            'content' => $reply,
        ];

        // Save to session (keep only last 20 messages to save tokens)
        session(['chat_history' => array_slice($history, -20)]);

        // Return the reply as JSON
        return response()->json([
            'reply' => $reply,
        ]);
    }

    /**
     * Clear chat history (start fresh)
     */
    public function clear()
    {
        session()->forget('chat_history');

        return response()->json([
            'status' => 'Chat cleared successfully',
        ]);
    }
}
