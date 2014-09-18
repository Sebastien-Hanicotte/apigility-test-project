<?php

namespace ConsumptionTest\Factory\Controller;

use Consumption\Factory\Controller\ProfileManagerControllerFactory;

class ProfileManagerControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $sm = $this->getControllerManager();
        $factory = new ProfileManagerControllerFactory();
        $service = $factory->createService($sm);
        $this->assertInstanceOf(
            'Consumption\Controller\ProfileManagerController',
            $service
        );
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getControllerManager()
    {
        $controllerManager = $this->getMockBuilder('Zend\Mvc\Controller\ControllerManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $controllerManager->expects($this->once())
                          ->method('getServiceLocator')
                          ->will($this->returnValue($this->getSmMock()));

        return $controllerManager;
    }

    private function getSmMock()
    {
        $importerInterface = $this->getMockBuilder('Acquisition\ImporterInterface')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $sm->expects($this->once())
           ->method('get')
           ->will($this->returnValue($importerInterface));

        return $sm;
    }
}
