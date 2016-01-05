<?php namespace PHPMinds\Model\Event;

interface SupporterInterface
{
    public function getName();

    public function getUrl();

    public function getTwitter();

    public function getEmail();

    public function getLogo();
}