<?php

return array_replace(
    require __DIR__ . '/definitions/auth.php',
    require __DIR__ . '/definitions/command-bus.php',
    require __DIR__ . '/definitions/http-cache.php',
    require __DIR__ . '/definitions/images.php',
    require __DIR__ . '/definitions/logger.php',
    require __DIR__ . '/definitions/signer.php'
);
