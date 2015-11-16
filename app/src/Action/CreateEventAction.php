<?php

namespace App\Action;

use App\Model\Event\Entity\Event;
use App\Model\Event\Entity\Venue;
use App\Model\Event\EventManager;
use App\Model\Event\Entity\Talk;
use App\Repository\SpeakersRepository;
use App\Validator\EventValidator;
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

    /**
     * @var EventManager
     */
    private $eventManager;

    private $eventSettings;

    public function __construct(Twig $view, LoggerInterface $logger, EventsService $eventService, $csrf, EventManager $eventManager, array $eventSettings = [])
    {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->eventService     = $eventService;
        $this->csrf             = $csrf;
        $this->eventManager     = $eventManager;
        $this->eventSettings    = $eventSettings;

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
                $eventInfo['title'] = $event['subject'];
                $eventInfo['description'] = $event['description'];
                $eventInfo['venue_id'] = $event['venue_id'];
                $date = \DateTime::createFromFormat('F jS Y', $event['date']);
                $eventInfo['date'] = $date->format("d/m/Y");
            }

        }

        $errors     = [];
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
                    $request->getParam('start_time'),
                    $this->eventService->getVenueById($request->getParam('venue')),
                    $this->eventManager->getSupporterByID($request->getParam('supporter'))
                );

                $this->eventService->createEvent($event);

                if (!$request->getParam('meetup_id')) {
                    if ((int)$this->eventService->createMeetup()->getStatusCode() !== 201) {
                        throw new \Exception('Could not create meetup event.');
                    }
                } else {
                    // Do not create a meetup
                    $this->eventService->getMeetupEvent()->setEventID((int)$request->getParam('meetup_id'));
                }

                if ((int)$this->eventService->createJoindinEvent($this->eventSettings['name'], $this->eventSettings['description'])->getStatusCode() !== 201) {
                    // TODO
                    // Delete meetup event which was just created.
                    throw new \Exception('Could not create Joindin event.');
                }

                if ((int)$this->eventService->createJoindinTalk()->getStatusCode() !== 201) {
                    // TODO
                    // Delete meetup event and JoindIn event just created.
                    throw new \Exception('Could not create Joindin talk.');
                }

                $eventEntity = $this->eventService->updateEvents();

                return $response->withStatus(302)->withHeader('Location', '/event/' . $eventEntity->getId());
            } catch (\Exception $e) {
                $frmErrors = $validator->getErrors();
                $errors[] = $e->getMessage();
            }

            // TODO
            // Send email
            // To UG admins
            // To speaker - with link to joind.in

        }




        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $this->view->render(
            $response,
            'admin/create-event.twig',
            [
                'speakers' => $speakers,
                'venues' => $venues,
                'eventInfo' => $eventInfo,
                'supporters' => $supporters,
                'nameKey' => $nameKey, 'valueKey' => $valueKey,
                'name' => $name, 'value' => $value,
                'errors' => $errors, 'frmErrors' => $frmErrors
            ]
        );

        return $response;
    }
}