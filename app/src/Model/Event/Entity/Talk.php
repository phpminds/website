<?php

namespace PHPMinds\Model\Event\Entity;

use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Exception\Model\Event\Entity\InvalidTalkTitle;
use PHPMinds\Model\Event\SpeakerInterface;
use PHPMinds\Model\Event\TalkInterface;

class Talk implements TalkInterface
{
    private $id;

    /**
     * @var String
     */
    private $title;

    /**
     * @var String
     */
    private $description;

    /**
     * @var \DateInterval
     */
    private $duration;

    /**
     * @var SpeakerInterface
     */
    private $speaker;

    /**
     * @var String
     */
    private $slides;

    /**
     * @param string           $title
     * @param string           $description
     * @param SpeakerInterface $speaker
     * @param string           $duration
     * @param string           $slides
     * @throws \Exception
     */
    public function __construct(string $title, string $description, SpeakerInterface $speaker, string $duration = 'PT2H', string $slides = '')
    {
        $this->title        = $title;
        $this->description  = $description;
        $this->speaker      = $speaker;
        $this->duration     = new \DateInterval($duration);
        $this->slides       = $slides;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return SpeakerInterface
     */
    public function getSpeaker() : SpeakerInterface
    {
        return $this->speaker;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @param array $params
     * @return TalkInterface
     * @throws \Exception
     */
    public static function create(array $params = []) : TalkInterface
    {
        $class = new self(
            $params['title'] ?? null,
            $params['description'] ?? null,
            $params['speaker'], // Expects Speaker object
            $params['duration'],
            $params['slides'] ?? ''
        );

        $class->setId($params['id'] ?? '');

        return $class;
    }
}