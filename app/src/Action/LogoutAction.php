<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Model\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

class LogoutAction
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

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->auth->clear();
        return $response->withStatus(302)->withHeader('Location', '/');
    }
}