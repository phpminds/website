<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class HomeAction
{
    private $view;
    private $logger;
    private $eventService;
    private $cache;

    public function __construct(Twig $view, LoggerInterface $logger, $eventService, $cache)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->cache = $cache;
        
    }

    public function dispatch($request, $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $event = $this->eventService->getEvent();


        $resWithETag = $this->cache->withETag($response, $event['id']);

        $this->view->render($response, 'home.twig', ['event' => $event]);
        return $resWithETag;
    }
}
