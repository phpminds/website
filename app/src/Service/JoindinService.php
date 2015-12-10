<?php
/**
 * Created by PhpStorm.
 * User: antonis
 * Date: 07/12/2015
 * Time: 23:58
 */

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
     * @param string $language
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createTalk($language = 'English - UK')
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events/' . $this->joindinEvent->getJoindinEventID() .'/talks'), [
            'json' => $this->joindinEvent->getCreateEventTitlePayload($this->event, $language),
            'headers' => $this->joindinEvent->getHeaders()
        ]);

        $this->joindinEvent->setTalkLocation($response->getHeader('location')[0]);

        return $response;
    }

    public function isEventApproved($meetupID)
    {

    }
}