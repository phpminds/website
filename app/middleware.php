<?php
// Application middleware


$app->add(function($request, $response, $next) use ($container) {

    $cspResponse = $response->withAddedHeader('Content-Security-Policy', $container->get('content-security-policies'));

    return $next($request, $cspResponse);
});



$app->add(new \Slim\HttpCache\Cache('public', 86400));

$app->add($container->get('Slim\Csrf\Guard'));

$app->add(new \pavlakis\cli\CliRequest());
