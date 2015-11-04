<?php

namespace App\Model;

class Email
{
    private $email;

    public function __construct($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email ' . $email . ' not valid.');
        }

        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }
}