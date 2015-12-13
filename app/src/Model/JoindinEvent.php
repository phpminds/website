<?php

namespace App\Model;

use App\Config\JoindinConfig;
use App\Model\Event\Event;
use App\Repository\FileRepository;

class JoindinEvent
{
    private $apiKey;
    private $baseUrl;
    private $frontendBaseUrl;
    private $callbackUrl;
    private $username;
    private $token;

    private $eventLocation;
    private $talkLocation;
    private $fileRepository;

    public function __construct(JoindinConfig $config, FileRepository $fileRepository)
    {
        $this->apiKey           = $config->apiKey;
        $this->baseUrl          = $config->baseUrl;
        $this->frontendBaseUrl  = $config->frontendBaseUrl;
        $this->callbackUrl      = $config->callback;
        $this->username         = $config->username;
        $this->fileRepository   = $fileRepository;
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
    public function getHeaders($userID = null)
    {
        return ['Authorization' => 'Bearer ' . $this->getToken($userID)];
    }

    /**
     * @param  mixed int|null $userID
     * @return String
     */
    public function getToken($userID = null)
    {
        if ($userID && !isset($this->token)) {
            $this->token = $this->fileRepository->get($userID . '_joindin');
        }
        return $this->token;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * @param string $end
     * @return string
     */
    public function getUrl($action = 'events', $end = '/')
    {
        return sprintf($this->baseUrl .'/%s' . $end, $action);
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getCreateEventPayload(Event $event)
    {
        return [
            'name' => $event->getName(),
            'description' => $event->getDescription(),
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
                $this->baseUrl . '/talks/'
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