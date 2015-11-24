<?php

namespace App\Tests\Model;

use App\Model\Email;
use App\Exception\Model\InvalidEmailException;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateAValidEmail()
    {
        $email = new Email('phpminds.org@gmail.com');

        $this->assertInstanceOf('App\Model\Email', $email);
    }

    /**
     * @expectedException App\Exception\Model\InvalidEmailException
     */
    public function testInvalidEmailThrowsException()
    {
        $email = new Email('invalid');
    }
}