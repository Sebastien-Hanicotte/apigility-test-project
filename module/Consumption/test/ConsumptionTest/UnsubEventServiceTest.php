<?php

namespace ConsumptionTest;

use Consumption\UnsubEventService;

class UnsubEventServiceTest extends \PHPUNit_Framework_Testcase
{
    public $collections = array(
        "C1", "C2", "C3"
    );

    protected $events = array(
        array(
            "_id" => '123',
            "d" => "2014-08-13",
            "i" => "127.0.0.1",
            "s" => "on",
            "da"  => array(
                "e" => "test@prismamedia.com",
                "c" => "M",
                "de" => array(
                    "C1", "C2"
                )
            )
        ),
    );

    protected $events2 = array(
        array(
            "_id" => '123',
            "d" => "2014-08-13",
            "i" => "127.0.0.1",
            "s" => "on",
            "da"  => array(
                "e" => "test@prismamedia.com",
                "c" => "M",
                "de" => array(
                    "all"
                )
            )
        ),
    );

    protected $event = array(
        "_id" => '123',
        "d" => "2014-08-13",
        "i" => "127.0.0.1",
        "s" => "on",
        "da"  => array(
            "e" => "test@prismamedia.com",
            "c" => "M",
            "de" => array(
                "C1", "C2"
            )
        )
    );

    public function testConsume()
    {
        $service = new UnsubEventService($this->getMongoDBMock());
        $this->assertEquals('123', $service->consume($this->events, '1'));

    }

    public function testUnsubFromAll()
    {
        $service = new UnsubEventService($this->getMongoDBMock());
        $this->assertEquals('123', $service->consume($this->events2, '1'));
    }

    /* ***********************************************************************
     * Helpers
     * ********************************************************************** */

    private function getMongoDBMock()
    {
        $database = $this->getMockBuilder('\MongoDB')
                    ->disableOriginalConstructor()
                    ->getMock();

        $database->expects($this->any())
                   ->method('selectCollection')
                   ->will($this->returnValue($this->getMongoCollectionMock()));

        $database->expects($this->any())
                   ->method('getCollectionNames')
                   ->will($this->returnValue($this->collections));

        return $database;
    }

    private function getMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('findOne')
                   ->will($this->returnValue($this->event));

       return $collection;
    }

}
