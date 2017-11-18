<?php
// Application middleware

$app->add(new Pavlakis\Middleware\Csp\CspMiddleware($container->get('csp.config'), false));

$app->add(new \Slim\HttpCache\Cache('public', 86400));

$app->add($container->get('Slim\Csrf\Guard'));

$app->add(new \pavlakis\cli\CliRequest());
