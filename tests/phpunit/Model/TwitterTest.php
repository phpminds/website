<?php

namespace App\Tests\Model;

use App\Model\Twitter;
use App\Exception\Model\InvalidTwitterHandleException;

class TwitterTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreateValidTwitter()
    {
        $twitter = new Twitter('@PHPMiNDS');

        $this->assertInstanceOf('App\Model\Twitter', $twitter);
    }

    /**
     * @expectedException App\Exception\Model\InvalidTwitterHandleException
     */
    public function testInvalidTwitterHandleThrowsException()
    {
        $twitter = new Twitter('@this-is-wrong');
    }

    /**
     * @expectedException App\Exception\Model\InvalidTwitterHandleException
     */
    public function testTooLongTwitterHandleThrowsException()
    {
        $twitter = new Twitter('@longer_than_fifteen_characters');
    }
}