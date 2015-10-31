<?php

namespace App\Model;

class MeetupEvent
{
    private $apiKey;
    private $baseUrl;
    private $groupUrlName;

    public function __construct($apiKey, $baseUrl, $groupUrlName)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->groupUrlName = $groupUrlName;
    }

    public function getAuthString()
    {
        return '?group_urlname='. $this->groupUrlName .'&key=' . $this->apiKey;
    }

    public function getEventUrl()
    {
        return $this->baseUrl .'/events/' . $this->getAuthString();
    }

    public function getVenuesUrl()
    {
        return $this->baseUrl . '/venues/' . $this->getAuthString();
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

    public function createEventPayload($talk = null)
    {
        return [
            'name' => '',
            'description' => '', // max - 50000 chars
            'venue_id' => '', // Numeric identifier of a venue
            'publish_status' => '', // draft
            'time' => '', // Event start time in milliseconds since the epoch, or relative to the current time in the d/w/m format.
        ];
    }

    public function saveEvent()
    {

    }
}