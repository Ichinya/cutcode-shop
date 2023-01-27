<?php

namespace App\Logging;

use Monolog\Logger;

class TelegramLoggerFactory
    {

    /**
     * Create a custom Monolog instance.
     * @param array{"level": string} $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
        {
        $logger = new Logger('telegram');
        $logger->pushHandler(new TelegramLoggerHandler($config));
//        $logger->setHandlers([new TelegramLoggerHandler($config)]);
        return $logger;
        }
    }
