<?php

namespace PHPMinds\Action;

use PHPMinds\Config\EventsConfig;
use PHPMinds\Factory\EventFactory;
use PHPMinds\Model\Auth;
use PHPMinds\Model\Event\EventManager;
use PHPMinds\Model\Form\CreateEventForm;
use PHPMinds\Validator\EventValidator;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use PHPMinds\Service\EventsService;
use Slim\Flash\Messages;
use Slim\Csrf\Guard;
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

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var EventsConfig
     */
    private $eventsConfig;

    /**
     * @var Messages
     */
    private $flash;


    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService,
                                Guard $csrf, EventManager $eventManager, EventsConfig $eventsConfig,
                                Auth $auth, Messages $flash)
    {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->eventService     = $eventService;
        $this->csrf             = $csrf;
        $this->eventManager     = $eventManager;
        $this->eventsConfig     = $eventsConfig;
        $this->auth             = $auth;
        $this->flash            = $flash;
    }

    public function dispatch(Request $request, Response $response, $args)
    {

        $meetupID = $request->getAttribute('meetup_id', null);
        $eventInfo = $this->eventService->getInfoByMeetupID($meetupID);

        if ($eventInfo->eventExists()) {
            $this->flash->addMessage('event', 'Event already exists. Check its status.');
            return $response->withStatus(302)->withHeader('Location', 'event-details/' . $meetupID);
        }

        if (!$eventInfo->isRegistered() && !is_null($meetupID)) {
            $this->flash->addMessage('event', 'No event found for meetupID provided. Please create a new event.');
            return $response->withStatus(302)->withHeader('Location', 'create-event');
        }

        $form = new CreateEventForm($this->eventManager, $this->eventService);
        
        if ($eventInfo->isRegistered()) {

            $form->setEventInfo($eventInfo);
        }

        $data = [
            'form' => $form,
            'errors' => $this->flash->getMessage('event') ?? [],
            'defaultTime' => $this->eventsConfig->defaultStartTime
        ];

        if ($request->isPost()) {

            $form->populate($request->getParams());
            if (!$form->isValid()) {

                // return response
                $data['errors'] = $form->getErrors();

                $data = array_merge($data, $this->getCsrfValues($request));

                $response->withStatus(304);

                $this->view->render(
                    $response,
                    'admin/create-event.twig',
                    $data
                );

                return $response;
            }

            try {

                $event = EventFactory::getEvent(
                    $form->getTalkTitle(), $form->getTalkDescription(),
                    $form->getEventDate(), $form->getSpeaker(), $form->getVenue(), $form->getSupporter(),
                    $this->eventsConfig->title, $this->eventsConfig->description
                );

                $createEventInfo = $this->eventService->createMainEvents(
                    $event,
                    $this->auth->getUserId(),
                    $meetupID
                );

                if (!is_null($createEventInfo['joindin_message'])) {
                    $this->flash->addMessage('event', $createEventInfo['joindin_message']);
                }


                return $response->withStatus(302)->withHeader('Location', 'event-details?meetup_id=' . $createEventInfo['meetup_id']);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());

                $this->logger->debug(print_r($data['errors'], true));
                $data['errors'] = array_merge($data['errors'], [$e->getMessage()]);
            }

        }

        $data = array_merge($data, $this->getCsrfValues($request));


        $this->view->render(
            $response,
            'admin/create-event.twig',
            $data
        );

        return $response;


    }

    protected function getCsrfValues(Request $request)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
            'nameKey'       => $nameKey,
            'valueKey'      => $valueKey,
            'name'          => $name,
            'value'         => $value
        ];
    }
}
