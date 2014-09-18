<?php

namespace ConsumptionTest\Factory\Service;

use Consumption\Factory\Service\BlacklistServiceFactory;

class BlacklistServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $sm = $this->getSmMock();
        $factory = new BlacklistServiceFactory();
        $service = $factory->createService($sm);
        $this->assertInstanceOf('Consumption\BlackListService', $service);
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
