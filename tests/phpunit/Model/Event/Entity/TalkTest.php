<?php

namespace PHPMinds\Tests\Model\Event\Entity;

use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Email;
use PHPMinds\Model\Twitter;
use PHPMinds\Exception\Model\Event\Entity\InvalidTalkTitle;

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

        $this->assertInstanceOf('PHPMinds\Model\Event\Entity\Talk', $talk);
    }
}