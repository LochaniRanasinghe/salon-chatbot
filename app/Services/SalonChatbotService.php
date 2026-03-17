<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SalonChatbotService
{
    private string $apiKey;
    private string $systemPrompt;

   public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->systemPrompt = <<<PROMPT
            You are a friendly virtual assistant for "Glow Salon & Spa".
            Your name is "Glow Assistant".
            You ONLY answer questions related to Glow Salon & Spa.

            SERVICES & PRICES:
            - Haircut (Women): LKR 1,500
            - Haircut (Men): LKR 800
            - Hair Coloring: LKR 3,500 - 8,000
            - Facial Treatment: LKR 2,500
            - Manicure: LKR 1,200
            - Pedicure: LKR 1,500
            - Bridal Package: LKR 25,000
            - Hair Straightening: LKR 5,000
            - Head Massage: LKR 1,000

            HAIRCUT RECOMMENDATIONS:
            - Short hair: Pixie cut, Bob cut, Layered bob, Textured crop
            - Medium hair: Shoulder length layers, Lob cut, Shag cut
            - Long hair: Layered cut, U-cut, Feather cut, Step cut
            - Curly hair: Deva cut, Shag cut, Layered curls
            - For personalized advice, always suggest booking a free consultation with our senior stylist.

            WORKING HOURS:
            - Monday to Saturday: 9:00 AM - 7:00 PM
            - Sunday: 10:00 AM - 4:00 PM

            LOCATION: 123 Main Street, Colombo 05
            PHONE: 011-2345678
            WHATSAPP: 077-1234567

            STRICT RULES (YOU MUST FOLLOW THESE):
            1. Be warm, friendly, and professional at ALL times.
            2. Answer in the SAME LANGUAGE the customer uses (Sinhala, Tamil, or English).
            3. Help customers book appointments, ask about services, and answer FAQs.
            4. If someone wants to book, collect: name, phone number, preferred date/time, and service.
            5. Keep responses short and concise (under 100 words).
            6. Always end with a helpful follow-up question.
            7. NEVER say "I can't help" or "I can't recommend" or "I'm not able to".
            8. If you don't know the answer, ALWAYS reply with: "That's a great question! Our senior stylist can give you the best advice on that. Would you like to book a free consultation? You can also call us at 011-2345678 or WhatsApp us at 077-1234567!"
            9. If someone asks about a topic NOT related to the salon (like politics, math, coding, etc.), say: "I'm Glow Assistant and I can only help with salon-related questions! Would you like to know about our services or book an appointment?"
            10. NEVER make up information. Only use the details provided above.
        PROMPT;
    }

    /**
     * Send a message to Gemini and get a response
     *
     * @param array $conversationHistory  - Array of previous messages
     * @return string                      - The bot's reply
     */
    public function chat(array $conversationHistory): string
    {
        $geminiMessages = [];

        foreach ($conversationHistory as $message) {
            $geminiMessages[] = [
                'role'  => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [
                    ['text' => $message['content']],
                ],
            ];
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->apiKey;

        $response = Http::withoutVerifying()
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'system_instruction' => [
                'parts' => [
                    ['text' => $this->systemPrompt],
                ],
            ],
            'contents' => $geminiMessages,
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 300,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Extract the text from Gemini's response
            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'Sorry, I could not understand that. Please try again.';
        }

        // ✅ ADD THIS — Log the actual error
        Log::error('Gemini API Error', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);


        if ($response->status() === 429) {
            return 'We are receiving too many requests. Please wait a moment.';
        }

        // If API fails, return a friendly fallback message
        return 'Sorry, I\'m having trouble right now. Please call us at 011-2345678 for assistance.';
    }
}