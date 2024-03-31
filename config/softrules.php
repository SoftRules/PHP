<?php declare(strict_types=1);

return [
    'forms' => [
        // Voeg hier je formulieren toe
        [
            'product' => 'softrules',
            'uri' => env('SOFTRULES_DAK_URI'),
            'username' => env('SOFTRULES_DAK_USERNAME'),
            'password' => env('SOFTRULES_DAK_PASSWORD'),
        ],
        [
            'product' => 'testpagina',
            'uri' => env('SOFTRULES_MYTP_TESTPAGINA_URI'),
            'username' => env('SOFTRULES_MYTP_TESTPAGINA_USERNAME'),
            'password' => env('SOFTRULES_MYTP_TESTPAGINA_PASSWORD'),
        ],
    ],
];
