<?php

namespace livetyping\hermitage\bootstrap;

use Dotenv\Dotenv;
use livetyping\hermitage\app\App;
use livetyping\hermitage\app\Sources;

/**
 * @param \livetyping\hermitage\app\Sources $sources
 *
 * @return \livetyping\hermitage\app\App
 */
function app(Sources $sources): App
{
    $app = new App($sources);
    require __DIR__ . '/../app/routes.php';

    return $app;
}

/**
 * @param string $path
 */
function load_dotenv(string $path)
{
    $path = rtrim($path, '/');
    if (file_exists($path . '/.env')) {
        (new Dotenv($path))->load();
    }
}
