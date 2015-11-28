<?php
namespace App\Action;

use App\Service\EventsService;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class HomeAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventsService
     */
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


        $event = $this->eventService->getLatestEvent();       
        $filter = $this->contentService->getTwigFilter();
        
        $this->view->getEnvironment()->addFilter($filter);


        $this->view->render($response, 'home.twig', ['event' => $event]);
        return $response;
    }
}
