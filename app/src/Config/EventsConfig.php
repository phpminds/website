<?php

namespace PHPMinds\Config;

/**
 * @property string $title
 * @property string $description
 */
class EventsConfig extends ConfigAbstract
{
    public function __construct($settings)
    {
        parent::__construct('events', $settings);
    }
}