<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Model\Auth;

final class LoginAction
{
    private $view;
    private $logger;
    private $auth;

    public function __construct(Twig $view, LoggerInterface $logger, Auth $auth)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
    }

    public function dispatch($request, $response, $args)
    {

        $this->view->render($response, 'login.twig', []);
        return $response;
    }
}