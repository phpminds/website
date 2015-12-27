<?php
// Application middleware


$app->add(new \Slim\HttpCache\Cache('public', 86400));



$app->add($container->get('Slim\Csrf\Guard'));

$app->add($container->get('PHPMinds\Middleware\AuthCheck'));

$app->add(new PHPMinds\Middleware\CliRequest());

