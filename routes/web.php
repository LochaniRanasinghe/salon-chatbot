<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Show the chat page
Route::get('/', [ChatbotController::class, 'index']);

// Send a message (AJAX endpoint)
Route::post('/chat/send', [ChatbotController::class, 'send']);

// Clear chat history
Route::post('/chat/clear', [ChatbotController::class, 'clear']);
