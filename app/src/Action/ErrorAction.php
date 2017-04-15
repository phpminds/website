<?php

namespace PHPMinds\Action;


use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ErrorAction
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

        $this->view->render($response, 'oops.twig');
        return $response;
    }
}