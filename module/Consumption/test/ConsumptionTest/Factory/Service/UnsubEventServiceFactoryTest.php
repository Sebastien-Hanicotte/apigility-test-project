<?php

namespace ConsumptionTest\Factory\Service;

use Consumption\Factory\Service\UnsubEventServiceFactory;

class UnsubEventServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $sm = $this->getSmMock();
        $factory = new UnsubEventServiceFactory();
        $service = $factory->createService($sm);
        $this->assertInstanceOf('Consumption\UnsubEventService', $service);
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getSmMock()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        return $sm;
    }
}
