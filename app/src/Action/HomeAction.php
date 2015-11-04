<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class HomeAction
{
    private $view;
    private $logger;
    private $eventService;

    public function __construct(Twig $view, LoggerInterface $logger, $eventService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
    }

    public function dispatch($request, $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        //$event = $this->eventService->getEvent();
        $event="";

        $this->view->render($response, 'home.twig', ['event' => $event]);
        return $response;
    }
}
