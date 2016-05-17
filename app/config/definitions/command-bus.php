<?php

use livetyping\hermitage\app\factories\CommandBus;
use livetyping\hermitage\foundation\bus\commands;
use livetyping\hermitage\foundation\bus\handlers;
use SimpleBus\Message\Bus\MessageBus;
use function DI\factory;
use function DI\get;
use function DI\object;

return [
    'command-bus.middleware' => [],

    'command-bus' => factory([CommandBus::class, 'create']),
    MessageBus::class => get('command-bus'),

    'command-bus.command-handler-map' => [
        commands\StoreImageCommand::class => handlers\StoreImageCommandHandler::class,
        commands\MakeImageVersionCommand::class => handlers\MakeImageVersionCommandHandler::class,
        commands\DeleteImageCommand::class => handlers\DeleteImageCommandHandler::class,
    ],

    // command handlers
    handlers\StoreImageCommandHandler::class => object(handlers\StoreImageCommandHandler::class),
    handlers\MakeImageVersionCommandHandler::class => object(handlers\MakeImageVersionCommandHandler::class),
    handlers\DeleteImageCommandHandler::class => object(handlers\DeleteImageCommandHandler::class),
];
