<?php

namespace App\Model;

use App\Exception\Model\InvalidTwitterHandleException;

class Twitter
{
    /**
     * @var String
     */
    private $twitterHandle;

    /**
     * Twitter constructor.
     * @param $twitterHandle
     * @throws InvalidTwitterHandleException
     */
    public function __construct($twitterHandle)
    {
        if (strlen($twitterHandle) > 15) {
            throw new InvalidTwitterHandleException("Twitter must be up to 15 characters.");
        }

        if (substr($twitterHandle, 0, 1) === '@') {
            $twitterHandle = substr($twitterHandle, 1);
        }

        preg_match('/^[A-Za-z0-9_]{1,15}$/', $twitterHandle, $matches);

        if (empty($matches)) {
            throw new InvalidTwitterHandleException("Twitter username only allows for aplhanumberic and underscores.");
        }

        $this->twitterHandle = '@' . $twitterHandle;
    }

    /**
     * @return String
     */
    public function __toString()
    {
        return $this->twitterHandle;
    }

}