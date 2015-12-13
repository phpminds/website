<?php
// DIC configuration

$container = $app->getContainer();

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withRedirect('/404');
    };
};

/* ---------- Configs ------------ */

$container['events.config'] = function ($c) {

    return new App\Config\EventsConfig($c->get('settings')['events']);

};


$container['meetup.config'] = function ($c) {
    $meetup = $c->get('settings')['meetups'];

    return new App\Config\MeetupConfig([
        'apiKey'        => $meetup['apiKey'],
        'baseUrl'       => $meetup['baseUrl'],
        'groupUrlName'  => $meetup['PHPMinds']['group_urlname'],
        'publishStatus' => $meetup['publish_status']
    ]);

};

$container['joindin.config'] = function ($c) {
    $joindin = $c->get('settings')['joindin'];

    return new App\Config\JoindinConfig([
        'apiKey'            => $joindin['key'],
        'baseUrl'           => $joindin['baseUrl'],
        'frontendBaseUrl'   => $joindin['frontendBaseUrl'],
        'callback'          => $joindin['callback'],
        'username'          => $joindin['username']
    ]);
};

$container['meetup.event'] = function ($c) {
    return new \App\Model\MeetupEvent($c->get('meetup.config'));
};

$container['joindin.event'] = function ($c) {

    return new \App\Model\JoindinEvent(
        $c->get('joindin.config'), $c->get('file.repository')
    );
};

$container['parsedown'] = function($c)
{
    return new Parsedown();
};

$container['service.joindin'] = function ($c) {
    return new \App\Service\JoindinService($c->get('http.client'), $c->get('joindin.event'));
};

$container['service.meetup'] = function ($c) {
    return new \App\Service\MeetupService($c->get('http.client'), $c->get('meetup.event'));
};


$container['service.content'] = function ($c) {
    $content = $c->get('settings')['content-folder'];
    return new \App\Service\ContentService($c->get('parsedown'),$content['location']);
};

$container['service.event'] = function ($c) {
    return new \App\Service\EventsService(
        $c->get('service.meetup'),
        $c->get('service.joindin'),
        $c->get('event.manager')
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

$container['file.repository'] = function ($c) {
    return new \App\Repository\FileRepository(
        $c->get('settings')['file_store']['path']
    );
};

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

$container['App\Action\NotFoundAction'] = function ($c) {

    return new App\Action\NotFoundAction(
        $c->get('view'), $c->get('logger')
    );
};

$container['App\Action\CommunityAction'] = function ($c) {

    return new App\Action\CommunityAction(
        $c->get('view'), $c->get('logger')
    );
};


$container['App\Action\CreateEventAction'] = function ($c) {

    return new App\Action\CreateEventAction(
        $c->get('view'), $c->get('logger'), $c->get('service.event'),
        $c->get('csrf'), $c->get('event.manager'), $c->get('events.config'),
        $c->get('auth.model'), $c->get('flash')
    );
};

$container['App\Action\EventDetailsAction'] = function ($c) {

    return new App\Action\EventDetailsAction(
        $c->get('view'), $c->get('logger'), $c->get('service.event'), $c->get('flash')
    );
};

$container['App\Action\CallbackAction'] = function ($c) {

    return new App\Action\CallbackAction(
        $c->get('logger'), $c->get('auth.model'), $c->get('file.repository')
    );
};

$container['App\Action\EventStatusAction'] = function ($c) {

    return new App\Action\EventStatusAction(
        $c->get('logger'), $c->get('service.event')
    );
};