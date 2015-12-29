<?php

namespace PHPMinds\Action;

use PHPMinds\Config\EventsConfig;
use PHPMinds\Factory\EventFactory;
use PHPMinds\Model\Auth;
use PHPMinds\Model\Event\EventManager;
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

        $speakers   = $this->eventManager->getSpeakers();
        $venues     = $this->eventService->getVenues();
        $supporters = $this->eventManager->getSupporters();

        $eventInfo = $this->eventService->getInfoByMeetupID($request->getParam('meetup_id'));
        if ($eventInfo['event_exists']) {
            $this->flash->addMessage('event', 'Event already exists. Check its status.');
            return $response->withStatus(302)->withHeader('Location', 'event-details?meetup_id=' . $request->getParam('meetup_id'));
        }

        $errors     = $this->flash->getMessage('event') ?? [];
        $frmErrors  = [];

        if ($request->isPost()) {
            $validator = new EventValidator($_POST);

            try {

                $validator
                    ->talkValidation()
                    ->dateValidation();

                if (!$validator->isValid()) {
                    throw new \Exception('Form not valid.');
                }

                $speaker    = $this->eventManager->getSpeakerById((int)$request->getParam('speaker'));
                $venue      = $this->eventService->getVenueById($request->getParam('venue'));
                $supporter  = $this->eventManager->getSupporterByID($request->getParam('supporter'));

                $date = \DateTime::createFromFormat(
                    "Y-m-d H:i",
                    $request->getParam('start_date') . ' '
                    . ($request->getParam('start_time') < 10 ? '0' . $request->getParam('start_time') :  $request->getParam('start_time'))

                );

                $event = EventFactory::getEvent(
                    $request->getParam('talk_title'), $request->getParam('talk_description'),
                    $date, $speaker, $venue, $supporter,
                    $this->eventsConfig->title, $this->eventsConfig->description
                );

                try {
                    $createEventInfo = $this->eventService->createMainEvents(
                        $event,
                        $this->auth->getUserId(),
                        $request->getParam('meetup_id')
                    );
                } catch (\Exception $e) {
                    throw $e;
                }

                if ((int)$createEventInfo['joindin_status'] === 202) {
                    // event pending. Save to DB and show message to user
                    $this->flash->addMessage('event', 'JoindIn Event is pending. Once approved, talk will be created automatically.');
                } else if ((int)$createEventInfo['joindin_status'] !== 201) {
                    $this->logger->debug("Could not create Joindin event. Please try again.");
                    $this->flash->addMessage('event', 'Could not create Joindin event. Please try again.');
                }

                return $response->withStatus(302)->withHeader('Location', 'event-details?meetup_id=' . $createEventInfo['meetup_id']);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
                $frmErrors = $validator->getErrors();
                $this->logger->debug(print_r($frmErrors, true));
                $errors[] = $e->getMessage();
            }

        }


        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $this->view->render(
            $response,
            'admin/create-event.twig',
            [
                'speakers'      => $speakers,
                'venues'        => $venues,
                'eventInfo'     => $eventInfo,
                'supporters'    => $supporters,
                'nameKey'       => $nameKey,
                'valueKey'      => $valueKey,
                'name'          => $name,
                'value'         => $value,
                'errors'        => $errors,
                'frmErrors' => $frmErrors
            ]
        );

        return $response;
    }
}
