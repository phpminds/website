<?php

namespace PHPMinds\Model\Event\Entity;

use PHPMinds\Model\Email;
use PHPMinds\Model\Event\NullSupporter;
use PHPMinds\Model\Event\SupporterInterface;
use PHPMinds\Model\Twitter;


class Supporter implements SupporterInterface
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

    public function exists()
    {
        return true;
    }
    
    /**
     * @param array $params
     * @return Supporter
     */
    public static function create(array $params = []) : SupporterInterface
    {
        if (empty($params)) {
            return new NullSupporter();
        }

        $class = new self(
            $params['name'] ?? null,
            $params['url'] ?? null,
            new Twitter($params['twitter'] ?? null) ?? null,
            new Email($params['email'] ?? null) ?? null,
            $params['logo'] ?? null
        );

        $class->setId($params['id'] ?? null);

        return $class;
    }

}