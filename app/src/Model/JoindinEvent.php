<?php

namespace App\Model;

use App\Model\Event\Event;

class JoindinEvent
{
    private $apiKey;
    private $baseUrl;
    private $frontendBaseUrl;
    private $callbackUrl;
    private $token;

    private $eventLocation;
    private $talkLocation;

    public function __construct($apiKey, $baseUrl, $frontendBaseUrl, $callback, $token)
    {
        $this->apiKey           = $apiKey;
        $this->baseUrl          = $baseUrl;
        $this->frontendBaseUrl  = $frontendBaseUrl;
        $this->callbackUrl      = $callback;
        $this->token            = $token;
    }

    /**
     * @param $talkID
     */
    public function setTalkID($talkID)
    {
        $this->talkID = $talkID;
    }

    /**
     * @param $url
     */
    public function setTalkURL($url)
    {
        $this->talkURL = $url;
    }

    /**
     * @return array
     */
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

    /**
     * For front-end authentication using callback
     *
     * @return string
     */
    public function authenticate()
    {
        return $this->frontendBaseUrl .'user/oauth_allow?api_key=' . $this->apiKey . '&callback=' . $this->callbackUrl;
    }

    /**
     * Gets URL prefixed with the base URL
     *
     * @param string $action
     * @return string
     */
    public function getUrl($action = 'events')
    {
        return sprintf($this->baseUrl .'/%s/', $action);
    }

    /**
     * @param Event $event
     * @param $name
     * @param $description
     * @return array
     */
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

    /**
     * @param Event $event
     * @param string $language
     * @return array
     */
    public function getCreateEventTitlePayload(Event $event, $language = 'English - UK')
    {
        $speakers = [$event->getTalk()->getSpeaker()->getFirstName() . ' ' . $event->getTalk()->getSpeaker()->getLastName()];

        $payload = [
            'talk_title' => $event->getTalk()->getTitle(),
            'talk_description' => $event->getTalk()->getDescription(),
            'language' => $language,
            'talk_type' => 'Talk',
            'start_date' => $event->getDate()->setTimezone(new \DateTimeZone( 'UTC' ))->format('Y-m-d H:i:s'),
            'speakers' => $speakers

        ];

        return $payload;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get the talk ID from the URL
     *
     * @return int
     */
    public function getTalkID()
    {
        $id = substr(
            $this->getTalkLocation(),
            strlen(
                $this->baseUrl . '/events/' .
                $this->getJoindinEventID() . '/talks/'
            )
        );


        if (substr($id, -1) == '/') {
            return (int)substr($id, 0, strlen($id) - 1);
        }

        if (substr($id, 0, 1) == '/') {
            return (int)substr($id, 1);
        }

        return (int)$id;
    }

    /**
     * Retrieve the front-end URL
     *
     * @return string
     */
    public function getTalkUrl()
    {
        return $this->frontendBaseUrl . '/talk/view/' . $this->getTalkID();

    }

    /**
     * @param $talkLocation
     */
    public function setTalkLocation($talkLocation)
    {
        $this->talkLocation  = $talkLocation;
    }

    /**
     * @return mixed
     */
    public function getTalkLocation()
    {
        return $this->talkLocation;
    }

    /**
     * @param $eventLocation
     */
    public function setEventLocation($eventLocation)
    {
        $this->eventLocation = $eventLocation;
    }

    /**
     * @return string
     */
    public function getEventLocation()
    {
        return $this->eventLocation;
    }

    /**
     * @return int
     */
    public function getJoindinEventID()
    {
        $id = substr($this->getEventLocation(), strlen($this->baseUrl . '/events/'));
        if (substr($id, -1) == '/') {
            return (int)substr($id, 0, strlen($id) - 1);
        }

        if (substr($id, 0, 1) == '/') {
            return (int)substr($id, 1);
        }

        return (int)$id;
    }
}