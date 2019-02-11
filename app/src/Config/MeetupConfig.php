<?php

namespace PHPMinds\Config;

/**
 * @property string $apiKey
 * @property string $baseUrl
 * @property string $groupUrlName
 * @property string $publishStatus
 */
class MeetupConfig extends ConfigAbstract
{
    public function __construct($settings)
    {
        parent::__construct('meetup', $settings);
    }
}