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

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->view->render(
            $response,
            'admin/create-event.twig',
            []
        );

        return $response;
    }
}