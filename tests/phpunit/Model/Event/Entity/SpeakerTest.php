<?php

namespace App\Tests\Model\Event\Entity;

use App\Model\Email;
use App\Model\Event\Entity\Speaker;
use App\Model\Twitter;

class SpeakerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Speaker
     */
    protected $speaker;

    public function setUp()
    {
        $this->speaker = new Speaker(
            'A',
            'Speaker',
            new Email('someone@somewhere.in'),
            new Twitter('@someone'),
            'me.com/look-at-me.jpg'
        );
    }

    public function testCanConstructValidSpeaker()
    {
        $this->assertInstanceOf('App\Model\Event\Entity\Speaker', $this->speaker);
    }

    public function testCanGetFirstName()
    {
        $this->assertSame('A', $this->speaker->getFirstName());
    }

    public function testCanGetLastName()
    {
        $this->assertSame('Speaker', $this->speaker->getLastName());
    }

    public function testCanGetEmail()
    {
        $this->assertSame(
            'someone@somewhere.in',
            $this->speaker->getEmail()->__toString()
        );
    }

    public function testCanGetTwitter()
    {
        $this->assertSame(
            '@someone',
            $this->speaker->getTwitter()->__toString()
        );
    }

    public function testCanGetAvatar()
    {
        $this->assertSame(
            'me.com/look-at-me.jpg',
            $this->speaker->getAvatar()
        );
    }
}