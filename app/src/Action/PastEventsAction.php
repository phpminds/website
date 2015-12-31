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
use Slim\HttpCache\CacheProvider;
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
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var CacheProvider
     */
    private $cache;

    public function __construct(Twig $view, LoggerInterface $logger,EventManager $eventManager, EventsService $eventService, CacheProvider $cache)
    {

        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;

        $this->eventManager = $eventManager;
        $this->cache = $cache;
    }

    public function eventByYearMonth(Request $request, Response $response, $args)
    {

        $year = intval($args["year"]);
        $month = intval($args["month"]);

        $eventMeta = $this->eventManager->getByYearMonth($year,$month);
        $event = $this->eventService->getEventById($eventMeta[0]['meetup_id']);
        $resWithETag = $this->cache->withETag($response, $eventMeta[0]['meetup_id']);
        $previousEvents= $this->eventService->getPastEvents();


        $this->view->render($response, 'event.twig', ['event' => $event,'eventMeta'=>$eventMeta[0],'previousEvents'=>$previousEvents]);

        return $resWithETag;
    }
}