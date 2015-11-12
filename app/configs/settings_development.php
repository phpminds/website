<?php
return [
    'settings' => [
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/../templates',
            'twig' => [
                'cache' => __DIR__ . '/../../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../../log/app.log',
        ],

        'meetups' => [
            "apiKey" => "68b7a6a5ac1b5174141057671c411",
            "baseUrl" => "https://api.meetup.com/2",
            "PHPMinds" =>  ["group_urlname" => "PHPMiNDS-in-Nottingham"]
        ],

        'db' => [
            "username" => "root",
            "password" => "Admin123",
            "host" => "127.0.0.1",
            "dbname" => "phpminds"
        ],

        'joindin' => [
            "baseUrl" => "http://api.dev.joind.in/",
            "key" => "f54108806306ce38d9c25df99931b9",
            "callback" => "phpminds.dev",
            "access_token" => "bdc3ceba53ea35ea"
        ],

        'auth-routes' => [
            '/admin',
            '/create-event'
        ],
         'content-folder' =>[
            'location' => __DIR__ . '/../templates/content/'
        ]
    ],

];
