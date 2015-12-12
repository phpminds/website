<?php

namespace App\Action;

use App\Repository\EventsRepository;
use App\Service\EventsService;
use App\Service\JoindinService;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class EventStatusAction
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventsService
     */
    private $eventsService;


    public function __construct(LoggerInterface $logger, EventsService $eventsService)
    {
        $this->logger           = $logger;
        $this->eventsService    = $eventsService;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        // ONLY WHEN CALLED THROUGH CLI
        if (PHP_SAPI !== 'cli') {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        if (!$request->getParam('event')) {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        // Default UserID for the required auth token
        $userID = 1;

        // Create talks for approved events
        echo $this->eventsService->manageApprovedEvents($userID);
        echo PHP_EOL;
        exit;
    }

}