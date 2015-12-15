<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch') 
    ->setName('homepage');

$app->get('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login');

$app->post('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login-post');

$app->get('/logout', 'App\Action\LogoutAction:dispatch')
    ->setName('logout');

$app->get('/404', 'App\Action\NotFoundAction:dispatch')
    ->setName('notfound');


// -- auth --
$app->get('/admin', 'App\Action\AdminDashboardAction:dispatch')
    ->setName('dashboard');

$app->get('/create-event', 'App\Action\CreateEventAction:dispatch')
    ->setName('create-event');

$app->post('/create-event', 'App\Action\CreateEventAction:dispatch')
    ->setName('create-event-post');

$app->post('/create-speaker', 'App\Action\CreateSpeakerAction:dispatch')
    ->setName('create-speaker');

$app->get('/event-details', 'App\Action\EventDetailsAction:dispatch')
    ->setName('event-details');

$app->get('/callback/{callback}', 'App\Action\CallbackAction:dispatch')
    ->setName('calbacks');

$app->get('/status', 'App\Action\EventStatusAction:dispatch')
    ->setName('status');

