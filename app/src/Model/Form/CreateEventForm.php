<?php

namespace PHPMinds\Model\Form;


use PHPMinds\Exception\Model\Event\Entity\InvalidTalkDescription;
use PHPMinds\Exception\Model\Event\Entity\InvalidTalkTitle;
use PHPMinds\Model\Event\EventManager;
use PHPMinds\Service\EventsService;

class CreateEventForm implements FormInterface
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var EventsService
     */
    protected $eventService;

    protected $errors = [];

    protected $data = [];

    protected static $eventInfo = null;

    public function __construct(EventManager $eventManager, EventsService $eventService)
    {
        $this->eventManager = $eventManager;
        $this->eventService = $eventService;
    }

    public function setEventInfo($eventInfo)
    {
        self::$eventInfo = $eventInfo;
    }

    public function getEventInfo()
    {
        if (is_null(self::$eventInfo)) {
            self::$eventInfo = $this->eventService->getInfoByMeetupID($this->get('meetup_id'));
        }

        return self::$eventInfo;
    }

    public function getVenues()
    {
        return $this->eventService->getVenues();
    }

    public function getSpeakers()
    {
        return $this->eventManager->getSpeakers();
    }

    public function getSupporters()
    {
        return $this->eventManager->getSupporters();
    }

    public function getSpeaker()
    {
        if (is_null($this->getEventInfo())) {
            return $this->getEventInfo()->getTalk()->getSpeaker();
        }

        return $this->eventManager->getSpeakerById((int)$this->get('speaker'));
    }

    public function getVenue()
    {
        return $this->getEventInfo()->getVenue();
    }

    public function getSupporter()
    {

        if (is_null($this->getEventInfo())) {
            return $this->getEventInfo()->getSupporter();
        }

        return $this->eventManager->getSupporterByID($this->get('supporter'));
    }

    public function getDate()
    {
        return $this->getEventInfo()->getDate();
    }

    public function getTalkTitle()
    {
        return $this->getEventInfo()->getTalk()->getTitle();
    }

    public function getTalkDescription()
    {
        return $this->getEventInfo()->getTalk()->getDescription();
    }

    
    public function getMeetupID()
    {
        return $this->getEventInfo()->getMeetupID();
    }

    protected function validateSupporter()
    {
        if (!$this->getSupporter()->exists()) {
            $this->addError('Please select a supporter.');
        }
    }

    protected function validateSpeaker()
    {
        if (!$this->getSpeaker()->exists()) {
            $this->addError('Please select a speaker.');
        }
    }

    protected function validate()
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method, 0, 8) === 'validate' && strlen($method) > 8) {
                $this->$method();
            }
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data)
    {
        $this->data = $data;
        $this->validate();
    }

    protected function addError($value)
    {
        $this->errors[] = $value;
    }

    protected function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }

        return $this->data[$key];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return count($this->errors) === 0;
    }
}