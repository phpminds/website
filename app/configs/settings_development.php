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

        'events' => [
            'title' => 'PHPMiNDS',
            'description' => "PHP Minds meet in Nottingham on the 3rd Thursday of each month.\n
            Follow us on Twitter @PHPMinds \n
            You can join us on IRC Freenode in #phpminds and on Slack (https://phpminds.herokuapp.com/)"
        ],

        'meetups' => [
            "apiKey" => "68b7a6a5ac1b5174141057671c411",
            "baseUrl" => "https://api.meetup.com/2",
            "publish_status" => 'draft', // always draft for Development
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
            "baseUrl" => "http://api.dev.joind.in/v2.1",
            "frontendBaseUrl" => "http://dev.joind.in",
            "key" => "22c3e521526e8a749227e464206577",
            "callback" => "phpminds.dev/callback/joindin",
            "token" => "9e22ee3d359eaec1" // access_token from callback
        ],

        'queue' => [
            'host' => '127.0.0.1',
            'port' => '5673',
            'user' => 'admin',
            'password' => 'Admin123',
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
