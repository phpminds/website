<?php

namespace App\Model\Event\Entity;

use App\Model\Email;
use App\Model\Twitter;

class Supporter
{
    public $id;

    private $name;

    private $url;

    private $twitter;

    private $email;

    private $logo;

    public function __construct($name, $url, Twitter $twitter, Email $email, $logo)
    {
        $this->name     = $name;
        $this->url      = $url;
        $this->twitter  = $twitter;
        $this->email    = $email;
        $this->logo     = $logo;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $params
     * @return Supporter
     */
    public static function create(array $params = []) : Supporter
    {
        $class = new self(
            $params['name'],
            $params['url'],
            new Twitter($params['twitter']),
            new Email($params['email']),
            $params['logo']
        );

        $class->setId($params['id']);

        return $class;
    }

}