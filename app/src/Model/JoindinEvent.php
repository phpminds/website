<?php

namespace App\Model;

use App\Model\Event\Event;

class JoindinEvent
{
    private $apiKey;
    private $baseUrl;
    private $callbackUrl;
    private $token;

    private $eventLocation;

    private $talkID;
    private $talkURL;

    public function __construct($apiKey, $baseUrl, $callback, $token)
    {
        $this->apiKey       = $apiKey;
        $this->baseUrl      = $baseUrl;
        $this->callbackUrl  = $callback;
        $this->token        = $token;
    }

    public function setTalkID($talkID)
    {
        $this->talkID = $talkID;
    }

    public function setTalkURL($url)
    {
        $this->talkURL = $url;
    }

    public function getHeaders()
    {
        return ['Authorization' => 'Bearer ' . $this->getToken()];
    }

    /**
     * @return String
     */
    public function getToken()
    {
        return $this->token;
    }

    public function authenticate()
    {
        return $this->baseUrl .'user/oauth_allow?api_key=' . $this->apiKey . '&callback=' . $this->callbackUrl;
    }

    public function getUrl($action = 'events')
    {
        return sprintf($this->baseUrl .'/%s/', $action);
    }

    public function getCreateEventPayload(Event $event, $name, $description)
    {
        return [
            'name' => $name . ' ' . $event->getDate()->format('F Y'),
            'description' => $description,
            'start_date' => $event->getDate()->setTimezone(new \DateTimeZone( 'UTC' ))->format('Y-m-d H:i:s'),
            'end_date' => $event->getEndDate()->setTimezone(new \DateTimeZone( 'UTC' ))->format('Y-m-d H:i:s'),
            'tz_continent' => $event->getVenue()->getContinent(),
            'tz_place' => 'London',
            'location' => $event->getVenue()->getName()
        ];
    }

    public function getCreateEventTitlePayload(Event $event, $language = 'English - UK')
    {
//        echo $event->getTalk()->getSpeaker();
//        echo PHP_EOL;
//        exit;
        // create talk
        // {"talk_title":"The first ever talk which works.","talk_description":"the first description","language":"English - UK","talk_type":"Talk","start_date":"2015-12-17T12:19:00+00:00","speakers":["Antonios Pavlakis"]}


        $speakers = [$event->getTalk()->getSpeaker()->getFirstName() . ' ' . $event->getTalk()->getSpeaker()->getLastName()];

        $payload = [
            'talk_title' => $event->getTalk()->getTitle(),
            'talk_description' => $event->getTalk()->getDescription(),
            'language' => $language,
            'talk_type' => 'Talk',
            'start_date' => $event->getDate()->setTimezone(new \DateTimeZone( 'UTC' ))->format('Y-m-d H:i:s'),
            'speakers' => $speakers

        ];


////
//        print_r(json_encode($payload));
//        echo PHP_EOL;
//        exit;

        return $payload;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getTalkID()
    {
        return $this->talkID;
    }

    public function getTalkUrl()
    {
        return $this->talkURL;
    }

    /**
     * @param $eventLocation
     */
    public function setEventLocation($eventLocation)
    {
        echo $eventLocation . ' <<<' . PHP_EOL;
        $this->eventLocation = $eventLocation;
    }

    /**
     * @return string
     */
    public function getEventLocation()
    {
        return $this->eventLocation;
    }

    public function getJoindinEventID()
    {
        $id = substr($this->getEventLocation(), strlen($this->baseUrl . '/events/'));
        if (substr($id, -1) == '/') {
            return substr($id, 0, strlen($id) - 1);
        }

        return $id;
    }
}