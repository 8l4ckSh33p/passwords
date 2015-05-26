<?php
return [
    'resources' => [
        'password' => ['url' => '/passwords'],
        'password_api' => ['url' => '/api/0.1/passwords']
    ],
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'password_api#preflighted_cors', 'url' => '/api/0.1/{path}',
         'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']]
    ]
];