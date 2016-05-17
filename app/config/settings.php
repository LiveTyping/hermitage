<?php

use function DI\env;
use function DI\string;

return [
    'root-dir' => dirname(dirname(__DIR__)),
    'storage-dir' => string('{root-dir}/storage'),

    'app.name' => env('APP_NAME', 'hermitage'),
];
