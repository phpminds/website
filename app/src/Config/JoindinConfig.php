<?php

namespace PHPMinds\Config;

/**
 * @property string $apiKey
 * @property string $baseUrl
 * @property string $frontendBaseUrl
 * @property string $callback
 * @property string $username
 */
class JoindinConfig extends ConfigAbstract
{
    public function __construct($settings)
    {
        parent::__construct('jondin', $settings);
    }
}