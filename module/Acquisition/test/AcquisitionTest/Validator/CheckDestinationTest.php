<?php

namespace AcquisitionTest\Validator;

use Acquisition\Validator\CheckDestination;

class CheckDestinationTest extends \PHPUnit_Framework_TestCase
{
    protected $trueValue = array("C1");
    protected $falseValue = array("foo");
    protected $noValue = array();

    public function testIsValid()
    {
        $validator = new CheckDestination();

        $this->assertFalse($validator('foo'));
        $this->assertFalse($validator($this->noValue));
        $this->assertFalse($validator($this->falseValue));
        $this->assertTrue($validator($this->trueValue));

    }
}
