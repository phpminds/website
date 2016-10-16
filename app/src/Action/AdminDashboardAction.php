<?php

namespace PHPMinds\Action;

use PHPMinds\Config\JoindinConfig;
use PHPMinds\Model\Auth;
use PHPMinds\Model\Event\EventManager;
use PHPMinds\Repository\FileRepository;
use PHPMinds\Service\EventsService;
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

    /**
     * @var JoindinConfig
     */
    private $joindinConfig;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    public function __construct(
        Twig $view,
        LoggerInterface $logger,
        EventsService $eventService,
        EventManager $eventManager,
        Auth $auth,
        JoindinConfig $joindinConfig,
        FileRepository $fileRepository
    ) {
        $this->view = $view;
        $this->logger = $logger;
        $this->eventService = $eventService;
        $this->eventManager = $eventManager;
        $this->fileRepository = $fileRepository;
        $this->auth = $auth;
    }

    public function dispatch($request, $response, $args)
    {

        $events = $this->eventService->getAll();

        $hasToken = false;
        if ($this->fileRepository->has($this->auth->getUserId() . '_joindin')) {
            $hasToken = true;
        }


        $this->view->render($response, 'admin/dashboard.twig', [
            'events' => $events,
            'has_token' => $hasToken,
            'joindin_callback' => $this->joindinConfig->callback
        ]);
        return $response;
    }
}
