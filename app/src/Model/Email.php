<?php

namespace App\Model;

use App\Exception\Model\InvalidEmailException;

class Email
{
    /**
     * @var String
     */
    private $email;

    public function __construct($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Email ' . $email . ' not valid.');
        }

        $this->email = $email;
    }

    /**
     * @return String
     */
    public function __toString()
    {
        return $this->email;
    }
}