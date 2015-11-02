<?php

namespace App\Action;

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

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, $csrf)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->csrf = $csrf;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
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


        // TODO
        // Create table to hold
            // Speaker
                // Name
                // email
                // Twitter
                // Talks
                // Profile (??) - Intro to speaker

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $this->view->render(
            $response,
            'admin/create-event.twig',
            [
                'nameKey' => $nameKey, 'valueKey' => $valueKey,
                'name' => $name, 'value' => $value
            ]
        );

        return $response;
    }
}