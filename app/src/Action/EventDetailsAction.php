<?php

namespace PHPMinds\Action;

use PHPMinds\Service\EventsService;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
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


    /**
     * @var Messages
     */
    private $flash;

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, Messages $flash)
    {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->eventService     = $eventService;
        $this->flash            = $flash;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        $meetupID = $request->getAttribute('meetup_id', false);

        if (!$meetupID) {
            $eventDetails['errors'][] = 'A meetup ID needs to be provided.';
        } else {
            $eventDetails = [
                'meetup_event' => $this->eventService->getEventById($meetupID),
                'event_info' => $this->eventService->getEventInfo($meetupID)
            ];
        }

        $eventDetails['errors'] = $this->flash->getMessage('event') ?? [];

        $this->view->render(
            $response,
            'admin/event-info.twig',
            $eventDetails
        );

        return $response;
    }

}