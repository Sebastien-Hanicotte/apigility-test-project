<?php

namespace ConsumptionTest\Factory\Service;

use Consumption\Factory\Service\SubEventServiceFactory;

class SubEventServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $sm = $this->getSmMock();
        $factory = new SubEventServiceFactory();
        $service = $factory->createService($sm);
        $this->assertInstanceOf('Consumption\SubEventService', $service);
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
