<?php

namespace App\Action;

use App\Service\EventsService;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class EventDetailsAction
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
        $this->view             = $view;
        $this->logger           = $logger;
        $this->eventService     = $eventService;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        $meetupID = (int)$request->getParam('meetup_id');
        if (!$meetupID) {
            // show error response
        }

        $this->view->render(
            $response,
            'admin/event-info.twig',
            [
                'meetup_event' => $this->eventService->getEventById($meetupID),
                'event_info' => $this->eventService->getEventInfo($meetupID)
            ]
        );

        return $response;
    }

}