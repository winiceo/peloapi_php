<?php

return [

    'sentinel' => require __DIR__.'/sentinel.php',

    'eloquent' => [
        'driver'    => $_SERVER['APP_DATABASE_DRIVER'],
        'host'      => $_SERVER['APP_DATABASE_HOST'],
        'database'  => $_SERVER['APP_DATABASE_DATABASE'],
        'username'  => $_SERVER['APP_DATABASE_USERNAME'],
        'password'  => $_SERVER['APP_DATABASE_PASSWORD'],
        'charset'   => $_SERVER['APP_DATABASE_CHARSET'],
        'collation' => $_SERVER['APP_DATABASE_COLLATION'],
        'prefix'    => $_SERVER['APP_DATABASE_PREFIX']
    ],

    'twig' => [
        'path' => [
            $app->getRootDir().'/templates'
        ],
        'options' => [
            'cache' => $app->getCacheDir().'/twig',
        ]
    ],

    'monolog' => [
        'name'  => 'app',
        'path'  => $app->getLogDir().'/',
        'level' => Monolog\Logger::ERROR
    ],
    'redis' => [
            'schema' => 'tcp',
            'host' => 'localhost',
            'port' => 6379,
      ],

    'secret-key' => 'sa9328343nd774788dhdhd-884747jjj99387jjhd-09',
    'parse'=>[

        'appid'=>'beenetwork',
        'marst_key'=>'e79dd55af42ae0916961',
        'server_url'=>'http://localhost:2233',
        'mount'=>'parse'
    ]


];
