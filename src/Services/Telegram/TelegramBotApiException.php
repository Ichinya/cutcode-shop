<?php

namespace Services\Telegram;

use Exception;

class TelegramBotApiException extends Exception
{
    public function __construct(string $message = 'Ошибка при отправке сообщения в телеграм')
    {
        parent::__construct($message);
    }
}
