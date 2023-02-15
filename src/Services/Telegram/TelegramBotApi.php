<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
{

    public const HOST = 'https://api.telegram.org/bot';

    /**
     * @throws TelegramBotApiException
     */
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::HOST . $token . '/sendMessage', ['chat_id' => $chatId, 'text' => $text])
                ->throw()
                ->json();
            return $response['ok'] ?? false;
        } catch (\Exception $exception) {
            report(new TelegramBotApiException($exception->getMessage(), $exception->getCode()));
        }
        return false;
    }
}
