<?php

namespace App\Model\Event\Entity;

use App\Model\Event\Entity\Speaker;
use App\Exception\Model\Event\Entity\InvalidTalkTitle;

class Talk
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
     * @var Speaker
     */
    private $speaker;

    /**
     * @var String
     */
    private $slides;

    public function __construct($title, $description, Speaker $speaker, $duration = 'PT2H', $slides = '')
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
     * @return Speaker
     */
    public function getSpeaker() : Speaker
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
     * @return Talk
     */
    public static function create(array $params = []) : Talk
    {
        $class = new self(
            $params['title'] ?? null,
            $params['description'] ?? null,
            $params['speaker'], // Expects Speaker object
            $params['duration'],
            $params['slides']
        );

        $class->setId($params['id']);

        return $class;
    }
}