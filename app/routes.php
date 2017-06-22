<?php
// Routes

$app->get('/', 'PHPMinds\Action\HomeAction:dispatch')
    ->setName('homepage');

$app->get('/login', 'PHPMinds\Action\LoginAction:dispatch')
    ->setName('login');

$app->post('/login', 'PHPMinds\Action\LoginAction:dispatch')
    ->setName('login-post');

$app->get('/logout', 'PHPMinds\Action\LogoutAction:dispatch')
    ->setName('logout');

$app->get('/404', 'PHPMinds\Action\NotFoundAction:dispatch')
    ->setName('notfound');

$app->get('/oops', 'PHPMinds\Action\ErrorAction:dispatch')
    ->setName('500');

$app->get('/event/{year:[0-9]+}/{month:[0-9]+}','PHPMinds\Action\PastEventsAction:eventByYearMonth')
    ->setName('pastEvents');

// -- auth --
$app->group('', function(){
    $this->get('/admin', 'PHPMinds\Action\AdminDashboardAction:dispatch')
        ->setName('dashboard');

    $this->get('/create-event[/{meetup_id}]', 'PHPMinds\Action\CreateEventAction:dispatch')
        ->setName('create-event');

    $this->post('/create-event/[{meetup_id}]', 'PHPMinds\Action\CreateEventAction:dispatch')
        ->setName('create-event-post');

    $this->post('/create-speaker', 'PHPMinds\Action\CreateSpeakerAction:dispatch')
        ->setName('create-speaker');

    $this->get('/event-details/[{meetup_id}]', 'PHPMinds\Action\EventDetailsAction:dispatch')
        ->setName('event-details');
})->add(function($request, $response, $next){

    if (!isset($_SESSION['auth'])) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    }

    return $next($request, $response);
})->add(
    // CSP set to report-only mode within the admin area until all issues are resolved
    new Pavlakis\Middleware\Csp\CspMiddleware($container->get('csp.config'))
);

$app->get('/callback/{callback}', 'PHPMinds\Action\CallbackAction:dispatch')
    ->setName('callbacks');

$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->setName('status');

