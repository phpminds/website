<?php

namespace PHPMinds\Model;

use PHPMinds\Config\MeetupConfig;
use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\EventModel;

class MeetupEvent
{
    private $apiKey;
    private $baseUrl;
    private $groupUrlName;
    private $publishStatus;

    private $eventLocation;
    private $eventID = null;

    public function __construct(MeetupConfig $config)
    {
        $this->apiKey           = $config->apiKey;
        $this->baseUrl          = $config->baseUrl;
        $this->groupUrlName     = $config->groupUrlName;
        $this->publishStatus    = $config->publishStatus;
    }

    /**
     * @param string $action
     * @param bool   $auth
     * @param array  $additionalApiParams
     * @return string
     */
    public function getUrl($action = 'events', bool $auth = true, $additionalApiParams = ['status'=>'past,upcoming'])
    {
        $authStr = '';
        if ($auth) {
            $authStr = $this->getAuthString($additionalApiParams);
        }
        return $this->baseUrl."/".urlencode($action)."/".$authStr;
    }

    /**
     * @param int $eventID
     */
    public function setEventID(int $eventID)
    {
        $this->eventID = $eventID;
    }

    /**
     * @return string
     */
    public function getGroupUrlName()
    {
        return $this->groupUrlName;
    }

    /**
     * @param array $params
     * @return string
     */
    public function getAuthString($params = [])
    {
        $defaults = [];
        if (!empty($params)) {
            $defaults = [ 'order' => 'time', 'desc' => 'true'];
        }

        $params = array_merge(
            [
                'group_urlname' => $this->groupUrlName,
                "key" => $this->apiKey
            ],
            $defaults,
            $params
        );
        $queryString = http_build_query($params);

        return '?'.$queryString;
    }

    /**
     * @return string
     */
    public function getEventUrl()
    {
        return $this->getUrl('events');
    }

    /**
     * @return string
     */
    public function getVenuesUrl()
    {
        return $this->getUrl('venues', true, []);
    }

    /**
     * @param array $event
     * @return array
     */
    public function formatResponse(array $event = [])
    {
        if (empty($event)) {
            return [];
        }

        $eventID = $event['id'];
        $subject = $event['name'];
        $eventDescription = isset($event['description']) ? $event['description'] : '';
        $eventUrl = $event['event_url'] ?? $event['link'];
        $groupName = $event['group']['name'];

        $eventDate = date('l jS F Y', $event['time'] / 1000);
        $eventTime = date('g:ia', $event['time'] / 1000);
        $eventCache = date('my', $event['time'] / 1000);
        $mindsUrl = date('Y',$event['time']/1000).'/'.date('m',$event['time']/1000) ?? '/';
        $venue = isset($event['venue']) ? $event['venue'] : '';

        $eventLocation = '';
        if ($venue) {
            $eventLocation = $venue['name'] . ', ' . $venue['address_1'] . ', ' . $venue['city'];
        }

        $eventDescription = $this->removeNameTwitterMention($eventDescription);

        return [
            'id'        => $eventID,
            'group'     => $groupName,
            'subject'   => $subject,
            'date_time' => $eventDate . ' at ' . $eventTime,
            'date'      => date ('F jS Y', $event['time'] / 1000),
            'time'      => $eventTime,
            'location'  => $eventLocation,
            'venue_id'  => $venue['id'] ?? '',
            'venue_name' => $venue['name'] ?? '',
            'venue_address' => $venue['address_1'] ?? '',
            'event_url'     => $eventUrl,
            'description'   => $eventDescription,
            'minds_url'     =>  $mindsUrl
        ];
    }

    /**
     * @param EventModel     $event
     * @return array
     */
    public function getCreateEventPayload(EventModel $event)
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
    public function getMeetupEventID()
    {
        if (!is_null($this->eventID)) {
            return $this->eventID;
        }

        $id = substr($this->getEventLocation(), strlen($this->baseUrl . '/event/'));
        if (substr($id, -1) == '/') {
            return (int)substr($id, 0, strlen($id) - 1);
        }

        if (substr($id, 0, 1) == '/') {
            return (int)substr($id, 1);
        }

        return (int)$id;
    }

    /**
     * @param string $eventDescription
     * @return string
     */
    public function removeNameTwitterMention(string $eventDescription)
    {
        $speakerInfo = [];

        // Find the author e.g. <p>First Last ( @Twitter )</p>
        preg_match('/<p>\s*[A-Za-z0-9].*\@[A-Za-z0-9_]{1,15}.*\)<\/p>/', $eventDescription, $speakerInfo);

        if (!empty($speakerInfo)) {
            return str_replace($speakerInfo[0], '', $eventDescription);
        }

        return $eventDescription;
    }
}
