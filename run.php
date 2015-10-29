<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

$env = '';
if (file_exists(__DIR__ .'/.env')) {
    $env = '_' . trim(file_get_contents(__DIR__ . '/.env'));
}

session_start();

// Instantiate the app
$settings = require __DIR__ . '/app/configs/settings' . $env .'.php';
$app = new \Slim\App($settings);

// Set up dependencies d
require __DIR__ . '/app/dependencies.php';

// Register middleware
require __DIR__ . '/app/middleware.php';

// Register routes
require __DIR__ . '/app/routes.php';

// Run!
$app->run();