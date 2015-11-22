<?php

namespace App\Model;

class Twitter
{
    private $twitterHandle;

    public function __construct($twitterHandle)
    {
        if (strlen($twitterHandle) > 15) {
            throw new \Exception("Twitter must be up to 15 characters.");
        }

        if (substr($twitterHandle, 0, 1) != '@') {
            $twitterHandle = '@' . $twitterHandle;
        }

        $this->twitterHandle = $twitterHandle;
    }

    public function __toString()
    {
        return $this->twitterHandle;
    }

}