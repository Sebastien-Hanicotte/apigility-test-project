<?php

namespace AcquisitionTest\Domain\ValueObject;

use Acquisition\Domain\ValueObject\AbstractValueObject;

class ImplAbstractValueObject extends AbstractValueObject
{
    protected $minify = array(
        'test' => 't',
    );

    public function formSetInput($rules)
    {
    }
}

class AbstractValueObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testLockAfterConstruct()
    {
        $domain = new ImplAbstractValueObject();

        $lockState = self::readAttribute($domain, 'isLock');
        $this->assertTrue($lockState);
    }

    public function testExceptionCannotWrite()
    {
        $initArray = array(
            'test' => 'value',
        );
        $domain = new ImplAbstractValueObject($initArray);

        $this->assertArrayHasKey('test', $domain);
        $this->assertEquals('value', $domain['test']);

        $this->setExpectedException('RuntimeException');
        $domain['test'] = 'new value';
    }

    public function testExceptionCannotPopulate()
    {
        $domain = new ImplAbstractValueObject();
        $this->assertArrayNotHasKey('test', $domain);
        $this->assertNull($domain['test']);

        $this->setExpectedException('RuntimeException');
        $initArray = array(
            'test' => 'value',
        );
        $domain->populate($initArray);
    }

    public function testExceptionSetInputFilterNotImplemented()
    {
        $domain = new ImplAbstractValueObject();
        $filterMock = $this->getMockBuilder('Zend\InputFilter\InputFilterInterface')
                           ->disableOriginalConstructor()
                           ->getMock();
        $this->setExpectedException('RuntimeException');
        $domain->setInputFilter($filterMock);
    }

    public function testExceptionGetInputFilterNotImplemented()
    {
        $domain = new ImplAbstractValueObject();
        $this->setExpectedException('RuntimeException');
        $domain->getInputFilter();
    }
}
