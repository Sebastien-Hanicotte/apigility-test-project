<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace ApplicationTest\Controller;

use ApplicationTest\Bootstrap;
use Application\Controller\IndexController;

use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    public function setUpWithRoute($route)
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new IndexController($route);
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Application\Application'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testRedirect()
    {
        $this->setUpWithRoute('/test/redirect');
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $location = $response->getHeaders()->get('Location', false);

        $this->assertNotFalse($location);
        $this->assertEquals('/test/redirect', $location->getUri());
    }

    public function testIndex()
    {
        $this->setUpWithRoute('/');
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
