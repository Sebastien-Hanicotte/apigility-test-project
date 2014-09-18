<?php

namespace AcquisitionTest\Validator;

use Acquisition\Validator\FormValidator;

class FormValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testDataIsValid()
    {
        $data = array(
            'foo' => 'bar',
            'bar' => 'tee'
        );
        $validator = new FormValidator($this->getFormMock(true), $data);

        $this->assertTrue($validator->isValid($data));
    }

    public function testIsValidException()
    {
        $data = array(
            'foo' => 'bar',
            'bar' => 'tee'
        );
        $validator = new FormValidator($this->getFormMock(false), $data);

        $this->setExpectedException('\Exception');
        $validator->isValid();
    }

    public function testBuildMessages()
    {
        $messages = array(
            'foo' => 'bar',
            'tee' => array(
                'foo'
            )
        );
        $validator = new FormValidator($this->getFormMock(false), null);

        $this->assertEquals(
            'foo => bar'. PHP_EOL .'<br/>tee => 0 => foo'. PHP_EOL .'<br/>',
            $validator->buildMessages($messages)
        );
    }

    public function testFormateDataToMongo()
    {
        $data = array(
            'date' => '10-09-2014',
            'type' => 1
        );

        $mapping = array(
            'date' => 'date',
            'type' => 'int'
        );

        $result = array(
            'date' => new \MongoDate(strtotime('10-09-2014')),
            'type' => new \MongoInt32(1)
        );

        $validator = new FormValidator($this->getFormMock(false), null);

        $this->assertEquals(
            $result,
            $validator->formateDataToMongo($data, $mapping)
        );
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getFormMock($boolean)
    {
        $messages = array(
            'foo' => array(
                'fooIsNotValid' => 'foo is not valid'
            )
        );
        $mock = $this->getMockBuilder('Zend\Form\Form')
                   ->disableOriginalConstructor()
                   ->getMock();

        $inputFilterMock = $this->getMockBuilder('Zend\InputFilter\InputFilterInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $inputFilterMock->expects($this->any())
                        ->method('getMessages')
                        ->will($this->returnValue($messages));

        $mock->expects($this->any())
           ->method('getInputFilter')
           ->will($this->returnValue($inputFilterMock));

        $mock->expects($this->any())
           ->method('setData');

        $mock->expects($this->any())
           ->method('isValid')
           ->will($this->returnValue($boolean));

        return $mock;
    }

}
