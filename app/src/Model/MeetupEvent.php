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

    public function getEventUrl()
    {
        return $this->baseUrl .'/events/?group_urlname='. $this->groupUrlName .'&key=' . $this->apiKey;
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
            'id' => $eventID,
            'group'     => $groupName,
            'subject' => $subject,
            'date_time' => $eventDate . ' at ' . $eventTime,
            'location' => $eventLocation,
            'event_url' => $eventUrl,
            'description' => $eventDescription
        ];
    }

    public function createEvent()
    {

    }

    public function saveEvent()
    {

    }
}