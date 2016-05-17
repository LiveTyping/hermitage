<?php

use livetyping\hermitage\app\handlers\ErrorLoggingDecorator;
use livetyping\hermitage\app\handlers\HttpErrorDecorator;
use Interop\Container\ContainerInterface;
use function DI\decorate;

return [
    'errorHandler' => decorate(function ($previous, ContainerInterface $c) {
        return new ErrorLoggingDecorator(
            $c->get('logger'),
            new HttpErrorDecorator($previous)
        );
    }),
    'phpErrorHandler' => decorate(function ($previous, ContainerInterface $c) {
        return new ErrorLoggingDecorator($c->get('logger'), $previous);
    }),
];
