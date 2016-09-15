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

$app->get('/event/{year:[0-9]+}/{month:[0-9]+}','PHPMinds\Action\PastEventsAction:eventByYearMonth')
    ->setName('pastEvents');

// -- auth --
$app->get('/admin', 'PHPMinds\Action\AdminDashboardAction:dispatch')
    ->setName('dashboard');

$app->get('/create-event/[{meetup_id}]', 'PHPMinds\Action\CreateEventAction:dispatch')
    ->setName('create-event');

$app->post('/create-event/[{meetup_id}]', 'PHPMinds\Action\CreateEventAction:dispatch')
    ->setName('create-event-post');

$app->post('/create-speaker', 'PHPMinds\Action\CreateSpeakerAction:dispatch')
    ->setName('create-speaker');

$app->get('/event-details/[{meetup_id}]', 'PHPMinds\Action\EventDetailsAction:dispatch')
    ->setName('event-details');

$app->get('/callback/{callback}', 'PHPMinds\Action\CallbackAction:dispatch')
    ->setName('calbacks');

$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->setName('status');

