<?php

namespace ConsumptionTest;

use Consumption\SubEventService;

class SubEventServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $events = array(
        array(
            "_id" => 12,
            "da"  => array(
                "de" => array("C1", "C2"),
                "p"  => array(
                    "e" => "test@prismamedia.com"
                ),
            ),
        ),
    );

    public function testConsume()
    {
        $service = new SubEventService($this->getMongoDBMock());

        $this->assertEquals(12, $service->consume($this->events, 1));
    }

    /* ***********************************************************************
     * Helpers
     * ********************************************************************** */

    private function getMongoDBMock()
    {
        $db = $this->getMockBuilder('\MongoDB')
                    ->disableOriginalConstructor()
                    ->getMock();

        $db->expects($this->any())
           ->method('selectCollection')
           ->will($this->returnValue($this->getMongoCollectionMock()));

        return $db;
    }

    private function getMongoCollectionMock()
    {
        $collection = $this->getMockBuilder('\MongoCollection')
                    ->disableOriginalConstructor()
                    ->getMock();

        $collection->expects($this->any())
                   ->method('findOne');

        return $collection;
    }
}
