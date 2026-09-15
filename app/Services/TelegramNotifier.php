<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    /**
     * Fire-and-forget ops alert. Missing Telegram env is a no-op.
     */
    public function notify(string $text): bool
    {
        $token = (string) config('services.telegram.bot_token');
        $chatId = (string) config('services.telegram.chat_id');
        if ($token === '' || $chatId === '') {
            return false;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        $topic = config('services.telegram.topic_id');
        if ($topic) {
            $payload['message_thread_id'] = is_numeric($topic) ? (int) $topic : $topic;
        }

        try {
            $response = Http::timeout(8)
                ->asForm()
                ->post('https://api.telegram.org/bot'.$token.'/sendMessage', $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('telegram notify failed', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
