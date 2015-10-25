<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class LoginAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    public function dispatch($request, $response, $args)
    {
//        echo '<pre>';
//        var_dump($_SESSION);exit;

        $this->view->render($response, 'login.twig', []);
        return $response;
    }
}