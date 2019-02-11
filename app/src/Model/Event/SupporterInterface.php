<?php namespace PHPMinds\Model\Event;

interface SupporterInterface
{
    public function getId();

    public function getName();

    public function getUrl();

    public function getTwitter();

    public function getEmail();

    public function getLogo();

    public function exists();
}