<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    public function testSendMessage(): void
    {
        Http::fake([
            TelegramBotApi::HOST . '*' => Http::response(['ok' => true]),
        ]);
        $response = TelegramBotApi::sendMessage('', 1, 'test');
        $this->assertTrue($response);
    }
}
