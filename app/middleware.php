<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add($container->get('auth.middleware'));