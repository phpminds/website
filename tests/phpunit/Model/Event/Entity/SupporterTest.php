<?php

namespace PHPMinds\Tests\Model\Event\Entity;

use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Email;
use PHPMinds\Model\Twitter;

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
        $this->assertInstanceOf('PHPMinds\Model\Event\Entity\Supporter', $this->supporter);
    }

    public function testGetSupporterName()
    {
        $this->assertSame('A Supporter', $this->supporter->getName());
    }


    public function testCreateInstanceWithNoParamsReturnsNullObject()
    {
        $supporter = Supporter::create();
        $this->assertFalse($supporter->exists());
    }


}