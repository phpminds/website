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

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
    }

    public function dispatch($request, $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $event = $this->eventService->getLatestEvent();


        $this->view->render($response, 'home.twig', ['event' => $event]);
        return $response;
    }
}
