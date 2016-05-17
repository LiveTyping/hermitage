<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../bootstrap/environment.php';

/** @var \livetyping\hermitage\app\App $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->run();
