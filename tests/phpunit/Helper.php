<?php

namespace PHPMinds\Tests;


use PHPMinds\Model\Email;
use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\Twitter;

class Helper extends \PHPUnit_Framework_TestCase
{
    protected $container = [];

    /**
     * @param $key
     * @param $value
     */
    protected function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * @param $key
     * @param callable $callback
     * @return bool
     */
    protected function get($key, callable $callback)
    {
        if (!array_key_exists($key, $this->container)) {
            $this->set($key, $callback());
        }

        return $this->container[$key];
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->get(
            'settings',
            function(){
                return require __DIR__ . '/../../app/configs/settings_test.php';
            }
        );
    }

    public function getEvent()
    {
        return $this->get(
            'event',
            function(){
                $email = new Email('phpminds.org@gmail.com');
                $twitter = new Twitter('@PHPMiNDS');

                $startDate = "17/12/2015";
                $startTime = '20:00';
                $eventDuration = 'PT2H';

                $speaker = new Speaker('A', 'Speaker', $email, $twitter);

                $talk = new Talk('A title', 'A description', $speaker, $eventDuration);

                $venue = new Venue('a little venue', 'by the sidewalk', 'Europe');
                $venue->setId(123);

                $supporter = new Supporter('a big fan', 'youareawso.me', $twitter, $email, 'http://nolo.go');

                return new EventModel(
                    $talk,
                    \DateTime::createFromFormat(
                        "d/m/Y H:i",
                        $startDate . ' ' . $startTime
                    ),
                    $venue,
                    $supporter
                );
            }
        );
    }
}