<?php

use livetyping\hermitage\app\actions;
use livetyping\hermitage\app\middleware\Authenticate;
use Slim\HttpCache\Cache;

/** @var \livetyping\hermitage\app\App $app */
$app->get('/{filename:.+}', actions\GetAction::class)->add(Cache::class);

$app->group('/', function () {
    /** @var \livetyping\hermitage\app\App $this */
    $this->post('', actions\StoreAction::class);
    $this->delete('{filename:.+}', actions\DeleteAction::class);
})->add(Authenticate::class);
