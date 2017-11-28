<?php
return [
    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => 'redis',
            'password' => null,
            'port' => '6379',
            'database' => 0,
        ],
    ],
];
