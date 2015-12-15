<?php
// Application middleware


$app->add(new \Slim\HttpCache\Cache('public', 86400));


$app->add($container->get('auth.middleware'));

$app->add($container->get('csrf'));

$app->add($container->get('auth.middleware'));

$app->add(new App\Middleware\CliRequest());

