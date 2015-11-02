<?php

namespace App\Model\Event;

class Speaker
{
    public $id;

    private $firstName;

    private $lastName;

    private $email;

    private $twitter;

    private $avatar;

    public function __construct($firstName, $lastName, $email, $twitter, $avatar = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        if (strpos($twitter, '@') === 0) {
            $twitter = substr($twitter, 1);
        }
        $this->twitter = $twitter;
        $this->avatar = $avatar;

    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }


}