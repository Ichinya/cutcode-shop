<?php

namespace Support\Logging;

use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Services\Telegram\TelegramBotApiContract;
use Services\Telegram\TelegramBotApiException;

class TelegramLoggerHandler extends AbstractProcessingHandler
{

    protected int $chatId;
    protected string $token;

    /**
     * @param array{'level':string, 'chat_id': int, 'token': string} $config
     */
    public function __construct(array $config)
    {
        $this->token = $config['token'];
        $this->chatId = (int)$config['chat_id'];
        $level = Logger::toMonologLevel($config['level']);
        parent::__construct($level);
    }


    /**
     * @param array{"message": string, "context": array, "level": int, "level_name": string, "channel": string, "datetime": \Monolog\DateTimeImmutable, "extra": array, "formatted": string} $record
     * @return void
     * @throws TelegramBotApiException
     */
    #[NoReturn] protected function write(array $record): void
    {
        /** @var TelegramBotApiContract $telegramApi */
        $telegramApi = app(TelegramBotApiContract::class);
        $telegramApi::sendMessage($this->token, $this->chatId, $record['formatted']);
    }
}
