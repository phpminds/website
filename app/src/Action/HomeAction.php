<?php
namespace PHPMinds\Action;

use PHPMinds\Service\ContentService;
use PHPMinds\Service\EventsService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\HttpCache\CacheProvider;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Twig_Environment;

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

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var CacheProvider
     */
    private $cache;


    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, ContentService $contentService, CacheProvider $cache)
    {
        $this->view         = $view;
        $this->logger       = $logger;
        $this->eventService = $eventService;
        $this->cache        = $cache;
        $this->contentService = $contentService;
    }

    public function dispatch(Request $request, Response $response, array $args)
    {
        $event = null;
        $eventExists = true;
        $previousEvents = null;
        try  {

            $event = $this->eventService->getLatestEvent();

            $previousEvents = $this->eventService->getPastEvents();

            $response = $this->cache->withETag($response, $event->getMeetupID());
        } catch (\Exception $e) {
            $eventExists = false;
        }

        $filter = $this->contentService->getTwigFilter();

        /** @var Twig_Environment $environment */
        $environment = $this->view->getEnvironment();
        $environment->addFilter($filter);

        $this->view->render($response, 'home.twig', [
                'event' => $event,
                'previousEvents' => $previousEvents,
                'eventExists' => $eventExists
        ]);

        return $response;
    }
}
