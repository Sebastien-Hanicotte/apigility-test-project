<?php

namespace ConsumptionTest;

use Consumption\BlacklistService;

class BlacklistServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $json = array(
        array(
            "d" => "2014-08-13",
            "i" => "127.0.0.1",
            "s" => "foo",
            "da"  => array(
                "e" => "test@prismamedia.com",
                "c" => "Il a fait une grosse bêtise"
            )
        ),
        array(
            "d"  => "2014-08-14",
            "i"   => "127.0.0.2",
            "s"   => "bar",
            "da"  => array(
                "e" => "test2@prismamedia.com",
                "c" => "Il a fait une grosse bêtise"
            )
        )
    );

    public function testAdd()
    {
        $collection = $this->getBadMongoCollectionMock();
        $db = $this->getMongoDBMock();
        $service = new BlacklistService($collection, $db);

        $this->assertEquals($this->json[0], $service->add($this->json[0]));
    }

    public function testGet()
    {
        $collection = $this->getMongoCollectionMock();
        $db = $this->getMongoDBMock();
        $service = new BlacklistService($collection, $db);

        $this->assertEquals($this->json[0], $service->get('test@prismamedia.com'));
    }

    public function testGetException()
    {
        $collection = $this->getBadMongoCollectionMock();
        $db = $this->getMongoDBMock();
        $service = new BlacklistService($collection, $db);

        $this->setExpectedException('Exception');
        $service->get('test@prismamedia.com');
    }

    public function testGetList()
    {
        $collection = $this->getMongoCollectionMockGetAll();
        $db = $this->getMongoDBMock();
        $service = new BlacklistService($collection, $db);

        $this->assertEquals($this->json, $service->getList());
    }

    public function testConsume()
    {
        $events = array(
            array("_id" => "ObjectId(5412ea9224a887ef160d3b55)", "da" => array("e" => "test@prismamedia.com")),
            array("_id" => "ObjectId(5412ea9224a887ef160d3b99)", "da" => array("e" => "test2@prismamedia.com"))
        );
        $cursor = "ObjectId(5412ea9224a887ef160d3b22)";

        $collection = $this->getMongoCollectionMockGetAll();
        $db = $this->getMongoDBMock();
        $service = new BlacklistService($collection, $db);

        $this->assertEquals("ObjectId(5412ea9224a887ef160d3b99)", $service->consume($events, $cursor));

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
                   ->will($this->returnValue($this->json[0]));

        $collection->expects($this->any())
                   ->method('findOne')
                   ->will($this->returnValue($this->json[0]));

        $collection->expects($this->any())
                    ->method('remove');

        return $collection;
    }

    private function getMongoCollectionMockGetAll()
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

    private function getBadMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('findOne');

       return $collection;
    }

    private function getMongoDBMock()
    {
        $db = $this->getMockBuilder('\MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $db->expects($this->any())
            ->method('getCollectionNames')
            ->will($this->returnValue(array('C1', 'C2')));

        $db->expects($this->any())
            ->method('selectCollection')
            ->will($this->returnValue($this->getMongoCollectionMock()));

        return $db;
    }

}
