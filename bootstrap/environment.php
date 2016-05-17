<?php

// load .env configuration if exists
$path = dirname(__DIR__);
if (file_exists($path . '/.env')) {
    (new \Dotenv\Dotenv($path))->load();
}
