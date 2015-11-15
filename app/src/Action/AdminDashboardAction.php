<?php

namespace App\Action;

use App\Model\Event\EventManager;
use App\Service\EventsService;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;

final class AdminDashboardAction
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

    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, EventManager $eventManager)
    {
        $this->view     = $view;
        $this->logger   = $logger;
        $this->eventService = $eventService;
        $this->eventManager = $eventManager;
    }

    public function dispatch($request, $response, $args)
    {
        $events     = $this->eventService->getAll();
        $speakers   = $this->eventManager->getSpeakers();
        $venues     = $this->eventService->getVenues();

        $this->eventService->mergeEvents($events, $speakers, $venues);

        $this->view->render($response, 'admin/dashboard.twig', [
            'events' => $events
        ]);
        return $response;
    }
}