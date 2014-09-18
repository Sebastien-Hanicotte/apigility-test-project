<?php

namespace ConsumptionTest;

use Consumption\EventService;

class EventServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConsumer()
    {
        $service = new EventService($this->getSmMock());

        $this->assertInstanceOf(
            "Consumption\SubEventService",
            $service->getConsumer(1)
        );
        $this->assertInstanceOf(
            "Consumption\BlacklistService",
            $service->getConsumer(3)
        );
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getSmMock()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $subEventService = $this->getMockBuilder("Consumption\SubEventService")
                   ->disableOriginalConstructor()
                   ->getMock();

        $blacklistService = $this->getMockBuilder("Consumption\BlacklistService")
                   ->disableOriginalConstructor()
                   ->getMock();

        $map = array(
            array("SubEventService", $subEventService),
            array("BlacklistService", $blacklistService)
        );

        $sm->expects($this->any())
           ->method("get")
           ->will($this->returnValueMap($map));

        return $sm;
    }
}
