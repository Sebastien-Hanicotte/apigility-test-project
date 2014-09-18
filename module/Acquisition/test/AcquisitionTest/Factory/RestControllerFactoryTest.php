<?php

namespace AcquisitionTest\Factory;

use Acquisition\Factory\Controller\RestControllerFactory;

class RestControllerFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $factory = new RestControllerFactory();
        $service = $factory->createService($this->getSmMockForJmSfMock());

        $this->assertNotNull($service);
        $this->assertInstanceOf('Acquisition\Controller\RestController', $service);
    }

    /*
    public function testExceptionCannotCreateServiceNoJournalManager()
    {
        $factory = new RestControllerFactory();

        $this->setExpectedException('RuntimeException');
        $factory->createService($this->getSmMockWithoutJmService());
    }

    public function testExceptionCannotCreateServiceNoServiceFormManager()
    {
        $factory = new RestControllerFactory();

        $this->setExpectedException('RuntimeException');
        $factory->createService($this->getSmMockWithoutSfService());
    }
    */
    /*     * **********************************************************************
     * Helpers
     * ********************************************************************** */

    private function getSmMock()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceManager')
                ->setMethods(array('getServiceLocator', 'get'))
                ->disableOriginalConstructor()
                ->getMock();

        $sm->expects($this->once())
           ->method('getServiceLocator')
           ->will($this->returnSelf());

        return $sm;
    }

    private function getSmMockForJmSfMock()
    {

        $jmMock = $this->getMockBuilder('Acquisition\DomainRepository\RepositoryManager')

                       ->disableOriginalConstructor()
                       ->getMock();

        $smMock = $this->getMockBuilder('Acquisition\ServiceForm')
                       ->disableOriginalConstructor()
                       ->getMock();

        $sm = $this->getSmMock();

        $map = array(
            array('journal', true, $jmMock),
            array('ServiceForm', true, $smMock),
        );

        $sm->expects($this->any())
           ->method('get')
           ->will($this->returnValueMap($map));

        return $sm;
    }
}
