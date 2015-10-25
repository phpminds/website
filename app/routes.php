<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch')
    ->setName('homepage');

$app->get('/admin', 'App\Action\AdminDashboardAction:dispatch')
    ->setName('dashboard');

$app->get('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login');

$app->post('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login-post');

$app->get('/logout', 'App\Action\LogoutAction:dispatch')
    ->setName('logout');