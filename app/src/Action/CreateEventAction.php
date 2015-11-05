<?php

namespace App\Action;

use App\Model\Event\Entity\Event;
use App\Model\Event\Entity\Venue;
use App\Model\Event\EventManager;
use App\Model\Event\Entity\Talk;
use App\Repository\SpeakersRepository;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Service\EventsService;
use Slim\Http\Request;
use Slim\Http\Response;

final class CreateEventAction
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

    private $csrf;

    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, $csrf, EventManager $eventManager)
    {
        $this->view         = $view;
        $this->logger       = $logger;
        $this->eventService = $eventService;
        $this->csrf         = $csrf;
        $this->eventManager = $eventManager;;
    }

    public function dispatch(Request $request, Response $response, $args)
    {

        if ($request->isPost()) {
            $event = new \App\Model\Event\Event(
              new Talk(
                  $request->getParam('talk_title'),
                  $request->getParam('talk_description'),
                  $this->eventManager->getSpeakerById((int)$request->getParam('speaker'))
                  ),
             $request->getParam('start_date'),
             $request->getParam('start_time'),
             $this->eventService->getVenueById($request->getParam('venue_id')),
             $this->eventManager->getSponsorById($request->getParam('sponsor'))
            );

            if ($this->eventService->createEvent($event) ) {
                // create entry to events

                // redirect to page with info to email to the speaker.
            }

            // http://www.meetup.com/meetup_api/docs/2/event/
            // create event in meetup.com
            // title
            // description
            // venue

            //  Create event / talk in joind.in
            // Add event for PHPMiNDS - Month Year (e.g. PHPMiNDS - December 2015)
            // Add talk for event

            // Send email
            // To UG admins
            // To speaker - with link to joind.in

            // Todo
            // Use rabbitmq to send emails :)
            //

        }




        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $this->view->render(
            $response,
            'admin/create-event.twig',
            [
                'speakers' => $this->eventManager->getSpeakers(),
                'venues' => $this->eventService->getVenues(),
                'nameKey' => $nameKey, 'valueKey' => $valueKey,
                'name' => $name, 'value' => $value
            ]
        );

        return $response;
    }
}