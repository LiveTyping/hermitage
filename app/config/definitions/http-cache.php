<?php

use Slim\HttpCache\Cache;
use function DI\env;
use function DI\get;
use function DI\object;

return [
    Cache::class => object(Cache::class)
        ->constructorParameter('type', env('HTTP_CACHE_TYPE', 'public'))
        ->constructorParameter('maxAge', env('HTTP_CACHE_MAX_AGE', 315360000)),
];
