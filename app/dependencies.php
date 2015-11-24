<?php
// DIC configuration

$container = $app->getContainer();

$container['meetup.event'] = function ($c) {
    $meetup = $c->get('settings')['meetups'];

    return new \App\Model\MeetupEvent(
        $meetup['apiKey'], $meetup['baseUrl'], $meetup['PHPMinds']['group_urlname'], $meetup['publish_status']
    );
};

$container['joindin.event'] = function ($c) {
    $joindin = $c->get('settings')['joindin'];


    return new \App\Model\JoindinEvent(
        $joindin['key'], $joindin['baseUrl'], $joindin['frontendBaseUrl'], $joindin['callback'], $joindin['token']
    );
};

$container['parsedown'] = function($c)
{
    return new Parsedown();
};



$container['service.content'] = function ($c) {
    $content = $c->get('settings')['content-folder'];
    return new \App\Service\ContentService($c->get('parsedown'),$content['location']);
};

$container['service.event'] = function ($c) {
    return new \App\Service\EventsService(
        $c->get('http.client'),
        $c->get('meetup.event'),
        $c->get('joindin.event'),
        $c->get('events.repository')
    );
};


$container['http.client'] = function ($c) {
    return new \GuzzleHttp\Client();
};

$container ['db'] = function ($c) {
    $db = $c->get('settings')['db'];

    return new \App\Model\Db (
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['username'], $db['password']
    );
};

// Repositories

$container['users.repository'] = function ($c) {
    return new \App\Repository\UsersRepository($c->get('db'));
};

$container['speakers.repository'] = function ($c) {
    return new \App\Repository\SpeakersRepository($c->get('db'));
};

$container['events.repository'] = function ($c) {
    return new \App\Repository\EventsRepository($c->get('db'));
};

$container['supporters.repository'] = function ($c) {
    return new \App\Repository\SupportersRepository($c->get('db'));
};

// Managers

$container['event.manager'] = function ($c) {
    return new \App\Model\Event\EventManager(
        $c->get('events.repository'),
        $c->get('speakers.repository'),
        $c->get('supporters.repository')
    );
};

$container['auth.middleware'] = function ($c) {
    return new App\Middleware\AuthCheck($_SESSION, 'auth', $c->get('settings')['auth-routes']);
};

$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
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
    return new App\Action\HomeAction(
        $c->get('view'), $c->get('logger'), $c->get('service.event'), $c->get('service.content')
    );
};

$container['App\Action\AdminDashboardAction'] = function ($c) {

    return new App\Action\AdminDashboardAction(
        $c->get('view'), $c->get('logger'), $c->get('service.event'), $c->get('event.manager')
    );
};

$container['App\Action\LoginAction'] = function ($c) {

    return new App\Action\LoginAction(
        $c->get('view'), $c->get('logger'), $c->get('auth.model'), $c->get('csrf')
    );
};

$container['App\Action\CreateSpeakerAction'] = function ($c) {

    return new App\Action\CreateSpeakerAction(
        $c->get('view'), $c->get('logger'), $c->get('speakers.repository')
    );
};

$container['App\Action\LogoutAction'] = function ($c) {

    return new App\Action\LogoutAction(
        $c->get('view'), $c->get('logger'), $c->get('auth.model')
    );
};

$container['App\Action\CreateEventAction'] = function ($c) {

    return new App\Action\CreateEventAction(
        $c->get('view'), $c->get('logger'), $c->get('service.event'),
        $c->get('csrf'), $c->get('event.manager'), $c->get('settings')['events'],
        $c->get('flash')
    );
};