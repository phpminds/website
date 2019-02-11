<?php

namespace PHPMinds\Model;

use PHPMinds\Config\JoindinConfig;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Repository\FileRepository;

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

    private $talkID;
    private $talkURL;

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
     * @param int $talkID
     */
    public function setTalkID(int $talkID)
    {
        $this->talkID = $talkID;
    }

    /**
     * @param string $url
     */
    public function setTalkURL(string $url)
    {
        $this->talkURL = $url;
    }

    /**
     * @param int|null $userID
     * @return array
     */
    public function getHeaders(int $userID = null)
    {
        return ['Authorization' => 'Bearer ' . $this->getToken($userID)];
    }

    /**
     * @param  int|null $userID
     * @return String
     */
    public function getToken(int $userID = null)
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
     * @param EventModel $event
     * @return array
     */
    public function getCreateEventPayload(EventModel $event)
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
     * @param EventModel $event
     * @param string $language
     * @return array
     */
    public function getCreateEventTitlePayload(EventModel $event, $language = 'English - UK')
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
     * @param string $talkLocation
     */
    public function setTalkLocation(string $talkLocation)
    {
        $this->talkLocation  = $talkLocation;
    }

    /**
     * @return string
     */
    public function getTalkLocation()
    {
        return $this->talkLocation;
    }

    /**
     * @param string $eventLocation
     */
    public function setEventLocation(string $eventLocation)
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