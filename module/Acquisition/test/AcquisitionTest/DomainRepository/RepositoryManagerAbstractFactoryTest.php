<?php

namespace AcquisitionTest\DomainRepository;

use Acquisition\DomainRepository\RepositoryManagerAbstractFactory;

class RepositoryManagerAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $result = array(
        'w' => '1',
        'wtimeout' => '10000'
        );

    public function testCreateService()
    {
        $sm = $this->getSmMock();
        $factory = new RepositoryManagerAbstractFactory();
        $this->assertFalse($factory->canCreateServiceWithName($sm, 'foo', 'bar'));
        $this->assertInstanceOf('Acquisition\DomainRepository\RepositoryManager',$factory->createServiceWithName($sm, 'foo', 'bar'));
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getSmMock()
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceManager')
                   ->setMethods(array('getServiceLocator', 'get'))
                   ->disableOriginalConstructor()
                   ->getMock();
        $sm->expects($this->any())
           ->method('getServiceLocator')
           ->will($this->returnSelf());

        return $sm;
    }

}
