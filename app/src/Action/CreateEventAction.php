<?php

namespace App\Action;

use App\Model\Auth;
use App\Model\Event\Entity\Event;
use App\Model\Event\Entity\Venue;
use App\Model\Event\EventManager;
use App\Model\Event\Entity\Talk;
use App\Repository\SpeakersRepository;
use App\Validator\EventValidator;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Service\EventsService;
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

    private $eventSettings;

    /**
     * @var Messages
     */
    private $flash;


    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService,
                                Guard $csrf, EventManager $eventManager, array $eventSettings = [],
                                Auth $auth, Messages $flash)
    {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->eventService     = $eventService;
        $this->csrf             = $csrf;
        $this->eventManager     = $eventManager;
        $this->eventSettings    = $eventSettings;
        $this->auth             = $auth;
        $this->flash            = $flash;
    }

    public function dispatch(Request $request, Response $response, $args)
    {

        $speakers   = $this->eventManager->getSpeakers();
        $venues     = $this->eventService->getVenues();
        $supporters = $this->eventManager->getSupporters();

        $eventInfo = ['title' => '', 'description' => ''];

        if ($request->getParam('meetup_id')) {
            $event = $this->eventService->getEventById((int)$request->getParam('meetup_id'));

            if(!empty($event)) {


                // todo
                // if event exists in DB - possibly event pending in joindin
                // redirect with message - functionality to create talk (only)
                if (!empty($this->eventManager->getDetailsByMeetupID($request->getParam('meetup_id')))) {
                    $this->flash->addMessage('event', 'Event already exists. Check its status.');
                    return $response->withStatus(302)->withHeader('Location', 'event-details?meetup_id=' . $request->getParam('meetup_id'));
                }

                $eventInfo['title'] = $event['subject'];
                $eventInfo['description'] = $event['description'];
                $eventInfo['venue_id'] = $event['venue_id'];
                $date = \DateTime::createFromFormat('F jS Y', $event['date']);
                $eventInfo['date'] = $date->format("d/m/Y");
            }

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

                $event = new \App\Model\Event\Event(
                    new Talk(
                        strip_tags($request->getParam('talk_title'), '<p><a><br>'),
                        strip_tags($request->getParam('talk_description'), '<p><img><a><br>'),
                        $this->eventManager->getSpeakerById((int)$request->getParam('speaker'))
                    ),
                    $request->getParam('start_date'),
                    $request->getParam('start_time') < 10 ? '0' . $request->getParam('start_time') :  $request->getParam('start_time'),
                    $this->eventService->getVenueById($request->getParam('venue')),
                    $this->eventManager->getSupporterByID($request->getParam('supporter'))
                );

                $event->setName($this->eventSettings['title']);
                $event->setDescription($this->eventSettings['description']);

                $this->eventService->createEvent($event);

                if (!$request->getParam('meetup_id')) {
                    if ((int)$this->eventService->createMeetup()->getStatusCode() !== 201) {
                        throw new \Exception('Could not create meetup event.');
                    }
                } else {
                    // Do not create a meetup
                    $this->eventService->getMeetupEvent()->setEventID((int)$request->getParam('meetup_id'));
                }

                try {
                    $createJoindInEvent = $this->eventService->createJoindinEvent(
                        $this->auth->getUserId()
                    );
                } catch (\Exception $e) {
                    throw $e;
                }

                if ((int)$createJoindInEvent->getStatusCode() === 202) {
                    // event pending. Save to DB and show message to user
                    $this->flash->addMessage('event', 'JoindIn Event is pending. Wait for approval before creating a Talk.');
                } else if ((int)$createJoindInEvent->getStatusCode() !== 201) {
                    $this->logger->debug("Could not create Joindin event. Please try again.");
                    $this->flash->addMessage('event', 'Could not create Joindin event. Please try again.');
                }

                $eventEntity = $this->eventService->updateEvents();

                return $response->withStatus(302)->withHeader('Location', 'event-details?meetup_id=' . $eventEntity->getMeetupID());
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
