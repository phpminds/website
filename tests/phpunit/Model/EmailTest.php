<?php

namespace PHPMinds\Tests\Model;

use PHPMinds\Model\Email;
use PHPMinds\Exception\Model\InvalidEmailException;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateAValidEmail()
    {
        $email = new Email('phpminds.org@gmail.com');

        $this->assertInstanceOf('PHPMinds\Model\Email', $email);
    }

    /**
     * @expectedException PHPMinds\Exception\Model\InvalidEmailException
     */
    public function testInvalidEmailThrowsException()
    {
        $email = new Email('invalid');
    }
}