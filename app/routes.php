<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch') 
    ->setName('homepage');

$app->get('/admin', 'App\Action\AdminDashboardAction:dispatch')
    ->setName('dashboard');

$app->get('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login');

