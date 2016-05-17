<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;
use function DI\env;
use function DI\get;
use function DI\object;
use function DI\string;

return [
    'logger.name' => get('app.name'),
    'logger.path' => env('LOGGER_PATH', string('{storage-dir}/logs/app.log')),
    'logger.handlers' => [object(StreamHandler::class)->constructor(get('logger.path'))],
    'logger.processors' => [
        object(PsrLogMessageProcessor::class),
        object(WebProcessor::class),
    ],

    'logger' => object(Logger::class)->constructor(
        get('logger.name'),
        get('logger.handlers'),
        get('logger.processors')
    ),
    LoggerInterface::class => get('logger'),
];
