<?php

namespace PHPMinds\Tests\Model;

use PHPMinds\Model\Twitter;
use PHPMinds\Exception\Model\InvalidTwitterHandleException;

class TwitterTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreateValidTwitter()
    {
        $twitter = new Twitter('@PHPMiNDS');

        $this->assertInstanceOf('PHPMinds\Model\Twitter', $twitter);
    }

    /**
     * @expectedException PHPMinds\Exception\Model\InvalidTwitterHandleException
     */
    public function testInvalidTwitterHandleThrowsException()
    {
        $twitter = new Twitter('@this-is-wrong');
    }

    /**
     * @expectedException PHPMinds\Exception\Model\InvalidTwitterHandleException
     */
    public function testTooLongTwitterHandleThrowsException()
    {
        $twitter = new Twitter('@longer_than_fifteen_characters');
    }
}