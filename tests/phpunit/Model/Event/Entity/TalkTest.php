<?php

namespace App\Tests\Model\Event\Entity;

use App\Model\Event\Entity\Talk;
use App\Model\Event\Entity\Speaker;
use App\Model\Email;
use App\Model\Twitter;
use App\Exception\Model\Event\Entity\InvalidTalkTitle;

class TalkTest extends \PHPUnit_Framework_TestCase
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

    public function testCanCreateValidTalk()
    {
        $talk = new Talk(
            'A valid title',
            'A valid description',
            $this->speaker
        );

        $this->assertInstanceOf('App\Model\Event\Entity\Talk', $talk);
    }
}