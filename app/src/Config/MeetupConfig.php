<?php

namespace PHPMinds\Config;


class MeetupConfig extends ConfigAbstract
{
    public function __construct($settings)
    {
        parent::__construct('meetup', $settings);
    }
}