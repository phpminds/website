<?php
// DIC configuration

use ParagonIE\CSPBuilder\CSPBuilder;
use PHPMinds\Entity\User;
use PHPMinds\Repository\UserRepository;
use ShaunHare\MeetupCache\MeetupCache;
use Stash\Driver\FileSystem;

$container = $app->getContainer();
$injector = new \pavlakis\seaudi\Injector($container);


$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {

        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withRedirect('/404');
    };
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->withRedirect('/oops');
    };
};

/* ---------- Configs ------------ */

$container['PHPMinds\Config\EventsConfig'] = function ($c) {

    return new PHPMinds\Config\EventsConfig($c->get('settings')['events']);

};


$container['meetup.config'] = function ($c) {
    $meetup = $c->get('settings')['meetups'];

    return new PHPMinds\Config\MeetupConfig([
        'apiKey'        => $meetup['apiKey'],
        'baseUrl'       => $meetup['baseUrl'],
        'groupUrlName'  => $meetup['PHPMinds']['group_urlname'],
        'publishStatus' => $meetup['publish_status']
    ]);

};

$container['joindin.config'] = function ($c) {
    $joindin = $c->get('settings')['joindin'];

    return new PHPMinds\Config\JoindinConfig([
        'apiKey'            => $joindin['key'],
        'baseUrl'           => $joindin['baseUrl'],
        'frontendBaseUrl'   => $joindin['frontendBaseUrl'],
        'callback'          => $joindin['callback'],
        'username'          => $joindin['username']
    ]);
};

$container['meetup.event'] = function ($c) {
    return new \PHPMinds\Model\MeetupEvent($c->get('meetup.config'));
};

$container['joindin.event'] = function ($c) {

    return new \PHPMinds\Model\JoindinEvent(
        $c->get('joindin.config'), $c->get('PHPMinds\Repository\FileRepository')
    );
};


$container['parsedown'] = function($c)
{
    return new Parsedown();
};

$container['service.joindin'] = function ($c) {
    return new \PHPMinds\Service\JoindinService($c->get('http.client'), $c->get('joindin.event'));
};

$container['service.meetup'] = function ($c) {
    
    $options = array('path' => __DIR__ . '/../cache/');
    $driver = new FileSystem($options);
    $meetupClient =  \DMS\Service\Meetup\MeetupOAuthClient::factory([
        'consumer_key'    => $c->get('meetup.config')->consumerKey,
        'consumer_secret'    => $c->get('meetup.config')->consumerSecret,
    ]);
    $meetupCacheClient = new MeetupCache($meetupClient, new \Stash\Pool($driver));

    return new \PHPMinds\Service\MeetupService(
        $meetupCacheClient,
        $c->get('meetup.event'),
        $c->get('meetup.config')
    );
};


$container['PHPMinds\Service\ContentService'] = function ($c) {
    $content = $c->get('settings')['content-folder'];
    return new \PHPMinds\Service\ContentService($c->get('parsedown'),$content['location']);
};

$container['PHPMinds\Service\EventsService'] = function ($c) {
    return new \PHPMinds\Service\EventsService(
        $c->get('service.meetup'),
        $c->get('service.joindin'),
        $c->get('PHPMinds\Model\Event\EventManager')
    );
};


$container['http.client'] = function ($c) {
    return new \GuzzleHttp\Client();
};

$container['Slim\HttpCache\CacheProvider'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container ['PHPMinds\Model\Db'] = function ($c) {
    $db = $c->get('settings')['db'];
    $db['port'] = $db['port'] ?? '3306'; // Added for BC

    return new \PHPMinds\Model\Db (
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';port=' . $db['port'], $db['username'], $db['password']
    );
};

$container['em'] = function ($c) {
    $settings = $c->get('settings')['doctrine'];
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['meta']['entity_path'],
        $settings['meta']['auto_generate_proxies'],
        $settings['meta']['proxy_dir'],
        $settings['meta']['cache'],
        false
    );
    return \Doctrine\ORM\EntityManager::create($settings['connection'], $config);
};

$container[UserRepository::class] = function ($c) {
    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $c->get('em');
    return $em->getRepository(User::class);
};

// Repositories

$container['PHPMinds\Repository\FileRepository'] = function ($c) {
    return new \PHPMinds\Repository\FileRepository(
        $c->get('settings')['file_store']['path']
    );
};

$injector->add('PHPMinds\Repository\UsersRepository');
$injector->add('PHPMinds\Repository\SpeakersRepository');
$injector->add('PHPMinds\Repository\EventsRepository');
$injector->add('PHPMinds\Repository\SupportersRepository');

// Managers

$container['PHPMinds\Model\Event\EventManager'] = function ($c) {
    return new PHPMinds\Model\Event\EventManager(
        $c->get('PHPMinds\Repository\EventsRepository'),
        $c->get('PHPMinds\Repository\SpeakersRepository'),
        $c->get('PHPMinds\Repository\SupportersRepository')
    );
};

$container['Slim\Csrf\Guard'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};

$container['PHPMinds\Model\Auth'] = function ($c) {
    $userRepository = $c->get(UserRepository::class);
    return new PHPMinds\Model\Auth($c->get('em'), $userRepository);
};

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['Slim\Views\Twig'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    $view->getEnvironment()->addGlobal('nonce', $c['global.nonce']);

    return $view;
};

$container['global.nonce'] = function($c) {
    return (new \Monolog\Processor\UidProcessor())->getUid();
};

$container['csp.config'] = function ($c) {

    $csp = CSPBuilder::fromFile(__DIR__ . '/configs/csp.json');
    $csp->nonce('script-src', $c['global.nonce']);

    return $csp;
};

// Flash messages
$injector->add('Slim\Flash\Messages');

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['Psr\Log\LoggerInterface'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------




$injector->add('PHPMinds\Action\NotFoundAction');
$injector->add('PHPMinds\Action\HomeAction');
$injector->add('PHPMinds\Action\LoginAction');
$injector->add('PHPMinds\Action\LogoutAction');
$injector->add('PHPMinds\Action\AdminDashboardAction');
$injector->add('PHPMinds\Action\EventDetailsAction');
$injector->add('PHPMinds\Action\CreateSpeakerAction');
$injector->add('PHPMinds\Action\CreateEventAction');
$injector->add('PHPMinds\Action\CallbackAction');
$injector->add('PHPMinds\Action\EventStatusAction');
$injector->add('PHPMinds\Action\PastEventsAction');
$injector->add('PHPMinds\Action\ErrorAction');