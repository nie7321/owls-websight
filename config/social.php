<?php

return [
    'subdomain' => env('APP_SOCIAL_SUBDOMAIN', 'owls.godless-internets.org'),
    'redirect' => env('APP_SOCIAL_REDIRECT_URL', env('APP_URL')),
    'atproto' => [
        'did' => env('ATPROTO_DID_PLC', 'did:plc:3cs2crtlwbbu5ui7ybqv2zlx'),
    ]
];
