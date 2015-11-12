<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class HomeAction
{
    private $view;
    private $logger;
    private $eventService;
    private $contentService; 

    public function __construct(Twig $view, LoggerInterface $logger, $eventService, $contentService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->contentService = $contentService;
    }

    public function dispatch($request, $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $event = $this->eventService->getEvent();
       

        $content = $this->contentService->getContentByName('test');
      
        $this->view->render($response, 'home.twig', ['event' => $event,'content'=>$content]);
        return $response;
    }
}
