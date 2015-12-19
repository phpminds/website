<?php

namespace App\Service;


use App\Model\Event\Event;
use App\Model\JoindinEvent;

class JoindinService
{

    /**
     * @var \GuzzleHttp\Client()
     */
    protected $httpClient;

    /**
     * @var \App\Model\JoindinEvent
     */
    protected $joindinEvent;

    /**
     * @var Event
     */
    private $event;


    public function __construct($httpClient, JoindinEvent $joindinEvent)
    {
        $this->httpClient = $httpClient;
        $this->joindinEvent = $joindinEvent;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return JoindinEvent
     */
    public function getJoindinEvent()
    {
        return $this->joindinEvent;
    }

    /**
     * @param $userID
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createEvent($userID)
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events'), [
            'json' => $this->joindinEvent->getCreateEventPayload($this->event),
            'headers' => $this->joindinEvent->getHeaders($userID)
        ]);

        $this->joindinEvent->setEventLocation($response->getHeader('location')[0]);

        return $response;
    }

    /**
     * @param Event $event
     * @param $userID
     * @param string $language
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createTalk(Event $event, $userID, $language = 'English - UK')
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events/' . $this->joindinEvent->getJoindinEventID() .'/talks'),
                [
                    'json' => $this->joindinEvent->getCreateEventTitlePayload($event, $language),
                    'headers' => $this->joindinEvent->getHeaders($userID)
                ]
        );

        $this->joindinEvent->setTalkLocation($response->getHeader('location')[0]);

        return $response;
    }

    /**
     * Approved events should show in the upcoming list
     *
     * @param array $events
     * @return bool
     */
    public function areEventsApproved(array &$events)
    {
        $response = $this->httpClient->get(
            $this->joindinEvent->getUrl('users/hosted?username=' . $this->joindinEvent->getUsername(), '')
        );

        // get the URI for the hosted events
        $usernameInfo = json_decode($response->getBody()->getContents(), true);

        $hostedEventsUri = '';
        if (!empty($usernameInfo['users'])) {
            if (!isset($usernameInfo['users'][0]['hosted_events_uri'])) {
                return false;
            }

            $hostedEventsUri = $usernameInfo['users'][0]['hosted_events_uri'];
        }

        $response = $this->httpClient->get(
            $hostedEventsUri
        );

        $hostedEvents = json_decode($response->getBody()->getContents(), true)['events'];

        if (empty($hostedEvents)) {
            return false;
        }

        $events = array_reduce($events, function($carry, $item){
            $carry[$item->joindin_event_name] = $item;
            return $carry;
        });

        $found = false;
        foreach ($hostedEvents as $event) {
            if (array_key_exists($event['name'], $events)) {
                $found = true;
                $events[$event['name']]->uri = $event['uri'];
            }
        }

        return $found;
    }
}