<?php

use livetyping\hermitage\app\signer\Signer;
use function DI\env;
use function DI\get;
use function DI\object;

return [
    'signer' => object(Signer::class)->constructor(env('SIGNER_ALGORITHM', 'sha256')),
    Signer::class => get('signer'),
];
