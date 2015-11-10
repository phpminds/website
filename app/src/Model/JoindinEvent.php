<?php

namespace App\Model;

use App\Model\Event\Event;

class JoindinEvent
{
    private $apiKey;
    private $baseUrl;
    private $token;

    private $talkID;
    private $talkURL;

    public function __construct($apiKey, $baseUrl, $token)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->token = $token;
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
}