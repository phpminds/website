<?php namespace PHPMinds\Model\Event;

class NullSupporter implements SupporterInterface
{
    public function getName()
    {
        return '';
    }

    public function getUrl()
    {
        return '';
    }

    public function getTwitter()
    {
        return '';
    }

    public function getEmail()
    {
        return '';
    }

    public function getLogo()
    {
        return '';
    }
}