<?php

use livetyping\hermitage\app\middleware\Authenticate;
use function DI\env;
use function DI\get;
use function DI\object;

return [
    Authenticate::class => object(Authenticate::class)
        ->constructorParameter('secret', env('AUTH_SECRET'))
        ->constructorParameter('timestampExpires', env('AUTH_TIMESTAMP_EXPIRES', 120)),
];
