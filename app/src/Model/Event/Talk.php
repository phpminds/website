<?php

namespace App\Model\Event;

use App\Model\Event\Speaker;

class Talk
{
    /**
     * @var String
     */
    private $title;

    /**
     * @var String
     */
    private $description;

    /**
     * @var Speaker
     */
    private $speaker;

    /**
     * @var String
     */
    private $slides;

    public function __construct($title, $description, Speaker $speaker, $slides = '')
    {
        $this->title = $title;
        $this->description = $description;
        $this->speaker = $speaker;
        $this->slides = $slides;
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
     * @return mixed
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->slides;
    }

}