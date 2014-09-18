<?php

namespace AcquisitionTest;

use Acquisition\ServiceForm;

class ServiceFormTest extends \PHPUnit_Framework_TestCase
{
    protected $json = array(
        array(
            "name" => "tee",
            "foo"  => "something",
            "bar"  => "anything"
        )
    );

    protected $data = array(
        "date" => "2014-11-09",
        "type" => 1,
        "data" => array(
            "email" => "test@test.com",
            "destination" => array("C1", "C3"),
            "addresses" => array(
                array("foo" => "1"),
                array("bar" => "2")
            )
        )
    );

    protected $journalRules = array(
        array(
            "name" => "date",
        ),
        array(
            "name" => "type",
        ),
        array(
            "name" => "data",
            "subform" => ["1", "2"]
        ),
        array(
            "name" => "email",
        ),
        array(
            "name" => "destination",
        ),
        array(
            "name" => "addresses",
            "subform" => "addresses"
        )
    );

    protected $dataRules = array(
        array(
            "name" => "email",
        ),
        array(
            "name" => "destination",
        )
    );

    protected $typeData = array(
        array(
            "name" => "foo",
            "type" => "int"
        )
    );

    public function testGet()
    {
        $collection  = $this->getMongoCollectionMock();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->assertEquals($this->json, $serviceForm->get('foo', 'forms'));
        $this->assertEquals(array('tee'), $serviceForm->get('foo', 'name'));
    }

    public function testEnsureHasExceptionByGetMethod()
    {
        $collection  = $this->getBadMongoCollectionMock();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->setExpectedException('\Exception');
        $serviceForm->get('foo');
    }

    public function testGetForm()
    {
        $collection  = $this->getMongoCollectionMock();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->assertInstanceOf('Zend\Form\Form' ,$serviceForm->getForm('foo', $this->json));
        // we check with the next test if the form is saved
        $this->assertInstanceOf('Zend\Form\Form' ,$serviceForm->getForm('foo', $this->json));
    }

    public function testAddSubFormInputFilter()
    {
        $collection  = $this->getMongoCollectionMockSubForm();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->assertInstanceOf(
            'Zend\InputFilter\InputFilter' ,
            $serviceForm->addSubFormInputFilter("foo", $this->data)
        );
    }

    public function testGetTypes()
    {
        $collection  = $this->getMongoCollectionMockTypeCase();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->assertEquals(array("foo" => "int") ,$serviceForm->getTypes('bar'));
    }

    public function testSetInputFilterException()
    {
        $collection  = $this->getMongoCollectionMock();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->setExpectedException('\Exception');
        $serviceForm->setInputFilter($this->getInputFilterInterfaceMock());
    }

    public function testCall()
    {
        $collection  = $this->getMongoCollectionMock();
        $validator   = $this->getValidatorPluginManagerMock();
        $serviceForm = new ServiceForm($collection, $validator);

        $this->assertEquals(array('tee'), $serviceForm->getNames('foo'));
        $this->assertEquals(array('something'), $serviceForm->getFoos('foo'));
        $this->assertEquals(array('anything'), $serviceForm->getBars('foo'));
        $this->assertNull($serviceForm->getNames());
    }

    /* ***********************************************************************
     * Helpers
     * ********************************************************************** */

    private function getMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('find')
                   ->will($this->returnValue($this->json));

        $collection->expects($this->any())
                   ->method('findOne')
                      ->will($this->returnValue($this->json));

        return $collection;
    }

    private function getMongoCollectionMockSubform()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('find')
                   ->will($this->returnValue($this->journalRules));

        $collection->expects($this->any())
                   ->method('findOne')
                   ->will($this->returnValue($this->journalRules));

        return $collection;
    }

    private function getMongoCollectionMockTypeCase()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('find')
                   ->will($this->returnValue($this->typeData));

        return $collection;
    }

    private function getBadMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('findOne');

       return $collection;
    }

    private function getValidatorPluginManagerMock()
    {
        $mock = $this->getMockBuilder('Zend\Validator\ValidatorPluginManager')
                    ->disableOriginalConstructor()
                    ->getMock();

        return $mock;
    }

    private function getInputFilterInterfaceMock()
    {
        $mock = $this->getMockBuilder('Zend\InputFilter\InputFilterInterface')
                    ->disableOriginalConstructor()
                    ->getMock();

        return $mock;
    }
}
