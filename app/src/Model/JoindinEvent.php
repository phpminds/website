<?php

namespace App\Model;

class JoindinEvent
{
    private $apiKey;
    private $baseUrl;
    private $callbackUrl;


    public function __construct($apiKey, $baseUrl, $callback)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->callbackUrl = $callback;
    }

    public function authenticate()
    {
        return $this->baseUrl .'user/oauth_allow?api_key=' . $this->apiKey . '&callback=' . $this->callbackUrl;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
