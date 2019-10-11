<?php

namespace PHPMinds\Tests\Model\Event\Entity;

use PHPMinds\Model\Event\Entity\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    protected $event;

    /**
     * TEST for events - please note not testing setters or getters cos no complexity
     */
    public function setUp()
    {
        $this->event = new Event(

            1,
            1,
            'test',
            1,
            'http://TEST',
            1,
            1,
            new \DateTime('now')

        );

    }

    public function testCanConstructValidEvent()
    {
        $this->assertInstanceOf(Event::class, $this->event);
    }

    public function testCreate()
    {
        $actual = Event::create(
            ['meetup_id' => 1,]
        );
        $this->assertInstanceOf(Event::class, $actual);

       $properties = get_object_vars($actual);

       self::assertNull($properties['id']);
       self::assertEquals(1, $actual->getMeetupID());

    }
}