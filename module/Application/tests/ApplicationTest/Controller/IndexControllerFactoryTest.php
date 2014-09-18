<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexControllerFactory;

/**
 * Description of GenericControllerFactoryTest
 */
class IndexControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new IndexControllerFactory();
        $config = array(
            'default_route' => '/nl/dashboard',
        );
        $controller = $factory->createService($this->getSmMock($config));

        $this->assertNotNull($controller);
        $this->assertInstanceOf('Application\Controller\IndexController', $controller);
    }

    /************************************************************************
     * Helpers
     ************************************************************************/

    private function getSmMock($config)
    {
        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceManager')
                   ->setMethods(array('getServiceLocator', 'get'))
                   ->disableOriginalConstructor()
                   ->getMock();
        $sm->expects($this->once())
           ->method('getServiceLocator')
           ->will($this->returnSelf());

        $sm->expects($this->once())
           ->method('get')
           ->with('Config')
           ->will($this->returnValue($config));

        return $sm;
    }
}
