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
        [
            'product' => 'volmachtproducten',
            'uri' => env('SOFTRULES_VOLMACHTPRODUCTEN_URI'),
            'username' => env('SOFTRULES_VOLMACHTPRODUCTEN_USERNAME'),
            'password' => env('SOFTRULES_VOLMACHTPRODUCTEN_PASSWORD'),
        ],
        [
            'product' => 'asr.vvp',
            'uri' => env('SOFTRULES_VOLMACHTPRODUCTEN_URI'),
            'username' => env('SOFTRULES_VOLMACHTPRODUCTEN_USERNAME'),
            'password' => env('SOFTRULES_VOLMACHTPRODUCTEN_PASSWORD'),
        ],
        [
            'product' => 'arag.rechtsbijstand',
            'uri' => env('SOFTRULES_VOLMACHTPRODUCTEN_URI'),
            'username' => env('SOFTRULES_VOLMACHTPRODUCTEN_USERNAME'),
            'password' => env('SOFTRULES_VOLMACHTPRODUCTEN_PASSWORD'),
        ],
        [
            'product' => 'vergelijking.avp',
            'uri' => env('SOFTRULES_VOLMACHTPRODUCTEN_URI'),
            'username' => env('SOFTRULES_VOLMACHTPRODUCTEN_USERNAME'),
            'password' => env('SOFTRULES_VOLMACHTPRODUCTEN_PASSWORD'),
        ],
        [
            'product' => 'vergelijking.personenauto',
            'uri' => env('SOFTRULES_VOLMACHTPRODUCTEN_URI'),
            'username' => env('SOFTRULES_VOLMACHTPRODUCTEN_USERNAME'),
            'password' => env('SOFTRULES_VOLMACHTPRODUCTEN_PASSWORD'),
        ],
    ],
];
