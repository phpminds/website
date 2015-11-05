<?php

namespace App\Model\Event\Entity;

use App\Model\Email;
use App\Model\Twitter;

class Speaker
{
    public $id;

    private $firstName;

    private $lastName;

    private $email;

    private $twitter;

    private $avatar;

    public function __construct($id, $firstName, $lastName, Email $email, Twitter $twitter, $avatar = null)
    {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
        $this->email        = $email;
        $this->twitter      = $twitter;
        $this->avatar       = $avatar;
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @param array $params
     * @return Speaker
     */
    public static function create(array $params = []) : Speaker
    {
        return new self(
            $params['id'],
            $params['first_name'],
            $params['last_name'],
            new Email($params['email']),
            new Twitter($params['twitter']),
            $params['avatar']
        );
    }

}