<?php

namespace PHPMinds\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;


class NotFoundAction
{
    private $view;
    private $logger;


    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;

    }

    public function dispatch(Request $request, Response $response, $args)
    {

        $this->view->render($response, '404.twig');
        return $response;
    }
}