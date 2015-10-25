<?php
// DIC configuration

$container = $app->getContainer();

$container['config'] = function ($c) {
    return json_decode(file_get_contents(__DIR__.'/configs/config.json'), true);
};

$container['meetup.event'] = function ($c) {
    $meetup = $c->get('config')['meetups'];

    return new \App\Model\MeetupEvent(
        $meetup['apiKey'], $meetup['baseUrl'], $meetup['PHPMinds']['group_urlname']
    );
};

$container['http.client'] = function ($c) {
    return new \GuzzleHttp\Client();
};

$container ['db'] = function ($c) {
    $db = $c->get('config')['db'];

    return new \App\Model\Db (
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['username'], $db['password']
    );
};

// Repositories

$container['users.repository'] = function ($c) {
    return new \App\Repository\UsersRepository($c->get('db'));
};


$container['auth.middleware'] = function ($c) {
    return new App\Middleware\AuthCheck($_SESSION, 'auth', $c->get('settings')['auth-routes']);
};

$container['auth.model'] = function ($c) {
    return new \App\Model\Auth(
        $c->get('users.repository')
    );
};

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container['App\Action\HomeAction'] = function ($c) {
    $eventService = new \App\Service\EventsService($c->get('http.client'), $c->get('meetup.event'));
    return new App\Action\HomeAction(
        $c->get('view'), $c->get('logger'), $eventService
    );
};

$container['App\Action\AdminDashboardAction'] = function ($c) {

    return new App\Action\AdminDashboardAction(
        $c->get('view'), $c->get('logger')
    );
};

$container['App\Action\LoginAction'] = function ($c) {

    return new App\Action\LoginAction(
        $c->get('view'), $c->get('logger'), $c->get('auth.model')
    );
};