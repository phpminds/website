<?php
/**
 * Created by PhpStorm.
 * User: shaunhare
 * Date: 30/12/15
 * Time: 00:15
 */

namespace PHPMinds\Action;


use PHPMinds\Model\Event\EventManager;
use PHPMinds\Service\EventsService;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class PastEventsAction
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
    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(Twig $view, LoggerInterface $logger,EventManager $eventManager, EventsService $eventService, Messages $flash)
    {

        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->flash = $flash;
        $this->eventManager = $eventManager;
    }

    public function eventByYearMonth(Request $request, Response $response, $args)
    {

        $year = intval($args["year"]);
        $month = intval($args["month"]);

        $eventMeta = $this->eventManager->getByYearMonth($year,$month);
        $event = $this->eventService->getEventById($eventMeta[0]['meetup_id']);
        exit(var_dump($eventMeta,$event));
    }
}