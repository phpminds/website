<?php

namespace App\Model;

use App\Model\Event\Entity\Talk;
use App\Model\Event\Entity\Venue;
use App\Model\Event\Event;

class MeetupEvent
{
    private $apiKey;
    private $baseUrl;
    private $groupUrlName;
    private $publishStatus;

    private $eventLocation;

    public function __construct($apiKey, $baseUrl, $groupUrlName, $publishStatus)
    {
        $this->apiKey           = $apiKey;
        $this->baseUrl          = $baseUrl;
        $this->groupUrlName     = $groupUrlName;
        $this->publishStatus    = $publishStatus;
    }

    public function getUrl($action = 'events')
    {
        return sprintf($this->baseUrl .'/%s/' . $this->getAuthString(), $action);
    }

    public function getAuthString()
    {
        return '?group_urlname='. $this->groupUrlName .'&key=' . $this->apiKey;
    }

    public function getEventUrl()
    {
        return $this->getUrl('events');
    }

    public function getVenuesUrl()
    {
        return $this->getUrl('venues');
    }

    public function formatResponse(array $events = [])
    {
        if (empty($events['results'])) {
            return [];
        }

        $event = $events['results'][0];

        $eventID = $event['id'];
        $subject = $event['name'];
        $eventDescription = isset($event['description']) ? $event['description'] : '';
        $eventUrl = $event['event_url'];
        $groupName = $event['group']['name'];


        $eventDate = date ('l jS F Y', $event['time'] / 1000);
        $eventTime = date ('g:ia', $event['time'] / 1000);
        $eventCache = date ('my', $event['time'] / 1000);

        $venue = isset($event['venue']) ? $event['venue'] : '';
        $eventLocation = '';
        if ($venue) {
            $eventLocation = $venue['name'] . ', ' . $venue['address_1'] . ', ' . $venue['city'];
        }

        return [
            'group'     => $groupName,
            'subject' => $subject,
            'date_time' => $eventDate . ' at ' . $eventTime,
            'location' => $eventLocation,
            'event_url' => $eventUrl,
            'description' => $eventDescription
        ];
    }

    /**
     * @param Event     $event
     * @return array
     */
    public function getCreateEventPayload(Event $event)
    {
        // x-www-form-urlencoded
        // have not tried using json
        $payload = [
            'name' => $event->getTalk()->getTitle(),
            'description' => $event->getTalk()->getDescription(), // max - 50000 chars
            'venue_id' => $event->getVenue()->getId(), // Numeric identifier of a venue
            'publish_status' => $this->publishStatus, // draft - for development
            'time' => $event->getDate()->getTimestamp() * 1000, // Event start time in milliseconds since the epoch, or relative to the current time in the d/w/m format.
            'venue_visibility' => 'members' // public OR members
        ];

        if ($this->publishStatus !== 'draft') {
            unset($payload['publish_status']);
        }

        return $payload;
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

    public function getMeetupEventID()
    {
        return substr($this->getEventLocation(), strlen($this->baseUrl . '/event/'));
    }
}