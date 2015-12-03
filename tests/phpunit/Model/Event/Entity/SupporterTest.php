<?php

namespace App\Tests\Model\Event\Entity;

use App\Model\Event\Entity\Supporter;
use App\Model\Email;
use App\Model\Twitter;

class SupporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Supporter
     */
    protected $supporter;


    public function setUp()
    {
        $this->supporter = new Supporter(
            'A Supporter',
            'http://phpminds.org',
            new Twitter('@PHPMinds'),
            new Email('someone@somewhere.com'),
            'http://phpminds.org/logo.png'
        );
    }

    public function testCanCreateValidSupporter()
    {
        $this->assertInstanceOf('App\Model\Event\Entity\Supporter', $this->supporter);
    }

    public function testGetSupporterName()
    {
        $this->assertSame('A Supporter', $this->supporter->getName());
    }

    /**
     * @expectedException App\Exception\Model\InvalidTwitterHandleException
     */
    public function testCreateInstanceWithNullTwitterThrowsException()
    {
        $supporter = Supporter::create();
    }


}