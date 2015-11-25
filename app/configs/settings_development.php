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
            "apiKey" => "23244d135e6824301121411f6c4c6612",
            "baseUrl" => "https://api.meetup.com/2",
            "PHPMinds" =>  ["group_urlname" => "PHPMiNDS-in-Nottingham"]
        ],

        'db' => [
            "username" => "root",
            "password" => "Admin123",
            "host" => "127.0.0.1",
            "dbname" => "phpminds"
        ],
        'events' => [
            'title' => 'PHPMiNDS',
            'description' => "PHP Minds meet in Nottingham on the 3rd Thursday of each month.\n
            Follow us on Twitter @PHPMinds \n
            You can join us on IRC Freenode in #phpminds and on Slack (https://phpminds.herokuapp.com/)"
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
