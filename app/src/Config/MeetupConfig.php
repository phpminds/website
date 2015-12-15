<?php

namespace App\Config;


class MeetupConfig extends ConfigAbstract
{
    public function __construct($settings)
    {
        parent::__construct('meetup', $settings);
    }
}