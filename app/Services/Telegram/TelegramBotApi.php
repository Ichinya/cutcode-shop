<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
    {

    public const HOST = 'http://api.telegram.org/bot';

    /**
     * @throws TelegramException
     */
    public static function sendMessage(string $token, int $chatId, string $text): bool
        {
        try
            {
            Http::get(self::HOST . $token . '/sendMessage', ['chat_id' => $chatId, 'text' => $text]);
            }
        catch (\Exception $exception)
            {
            throw new TelegramException($exception->getMessage(), $exception->getCode());
            }
        return true;
        }
    }
