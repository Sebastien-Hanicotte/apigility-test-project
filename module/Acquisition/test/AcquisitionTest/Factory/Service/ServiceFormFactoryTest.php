<?php

namespace AcquisitionTest\Factory\Service;

use Acquisition\Factory\Service\ServiceFormFactory;

class ServiceFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCannotCreateServiceException()
    {
        $sm = $this->getSmMockException();
        $factory = new ServiceFormFactory();
    $this->setExpectedException('Exception');
        $factory->createService($sm);

    }

  public function testCreateService()
  {
        $sm = $this->getSmMock();
        $factory = new ServiceFormFactory();
        $service = $factory->createService($sm);
        $this->assertInstanceOf('Acquisition\ServiceForm', $service);
  }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getSmMockException()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();
        $sm->expects($this->any())
           ->method('get')
           ->with('ValidatorManager')
           ->will($this->throwException(new \Exception()));

        return $sm;
    }

    private function getSmMock()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();
        $sm->expects($this->any())
           ->method('get')
           ->with('ValidatorManager')
           ->will($this->returnValue($this->getValidatorManagerMock()));

        return $sm;
    }

    private function getValidatorManagerMock()
    {
        $vm = $this->getMockBuilder('Zend\Validator\ValidatorPluginManager')
                   ->disableOriginalConstructor()
                   ->getMock();

        return $vm;
    }

}
