<?php

namespace AcquisitionTest\Controller;

use AcquisitionTest\Bootstrap;

use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Stdlib\Parameters;
use Acquisition\Controller\RestController;

use PHPUnit_Framework_TestCase;

class RestControllerTest extends PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected $data = array(
        'email' => 'foo@bar.com',
        'data'  => array(
            'profil' => array(
                'addresses' => array(
                    array(
                        'address' => 'foo'
                    )
                )
            ),
        ),
        'type' => 1
    );

    protected $dataUnsub = array(
        'data'  => array(
            'profil' => array(
                'email' => 'foo@bar.com',
                'addresses' => array(
                    array(
                        'address' => 'foo'
                    )
                )
            ),
        ),
        'type' => 2
    );

    protected $badData = array(
        'email' => 'foo@bar.com',
        'data'  => array(
            'profil' => array(
                'addresses' => array(
                    array(
                        'address' => 'foo'
                    )
                )
            ),
        ),
        'type' => 'foo'
    );

    protected $required = array(
        array('name'=>'email','required'=>true),
    );

    protected $mapping = array(
        'email'=>'e',
        'from'=>'fr',
    );

    protected $types = array(
        'email'=>'string',
        'from'=>'string',
    );

    protected $blacklist = array(
        "data" => array(
            "emails" => array(
                "foo@bar.com",
                "foo2@bar.com"
            )
        ),
        "type" => 3
    );

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();

        $importerInterface = $this->getImporterInterfaceMock($this->data);
        $serviceForm = $this->getServiceFormMock();
        $this->controller = new RestController($importerInterface, $serviceForm);

        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event = new MvcEvent();

        $config = $this->serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);

    }

    public function testGetCanBeAccessed()
    {

        $this->routeMatch->setParam('id', 'foo@bar.com');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetList()
    {
        $this->request->setMethod('get');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetListReturnJsonModel()
    {
        $this->request->setMethod('get');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getList();

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
    }

    public function testUpdateCanBeAccessed()
    {

        $this->routeMatch->setParam('id', 'foo@bar.com');
        $this->request->setMethod('put');
        $this->request->setPost(new Parameters($this->data));

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testDeleteCanBeAccessed()
    {
        $this->routeMatch->setParam('id', 'foo@bar.com');
        $this->request->setMethod('delete');

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testCreateCanBeAccessed()
    {
        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->data));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateUnsubCase()
    {
        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->dataUnsub));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateBlacklistArrayOfEmailCase()
    {
        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->blacklist));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateExceptionType()
    {
        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->badData));

        $this->setExpectedException('\InvalidArgumentException');
        $this->controller->dispatch($this->request);
    }

    public function testCreateResponse400()
    {
        $messages = array(
            'test' => 'it is just a test, do not care',
        );
        $this->required[] = array('name'=>'from', 'required'=> true);
        $importerInterface = $this->getImporterInterfaceMock($this->data);
        $serviceForm = $this->getServiceFormMockFalseCase($messages);
        $this->controller = new RestController($importerInterface, $serviceForm);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);

        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->data));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(400, $response->getStatusCode());

    }

    public function testCreateResponse409()
    {
        $messages = array(
            'email_found' => ''
        );
        $this->required[] = array('name'=>'from', 'required'=> true);
        $importerInterface = $this->getImporterInterfaceMock($this->data);
        $serviceForm = $this->getServiceFormMockFalseCase($messages);
        $this->controller = new RestController($importerInterface, $serviceForm);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);

        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($this->data));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(409, $response->getStatusCode());

    }

    /************************************************************************
     * Helpers
     ************************************************************************/
    private function getImporterInterfaceMock($data)
    {
        $mock = $this->getMockBuilder('Acquisition\DomainRepository\RepositoryManager')
                     ->disableOriginalConstructor()
                     ->getMock();

        $mock->expects($this->any())
             ->method('fetchAll')
             ->will($this->returnValue($data));

        $mock->expects($this->any())
             ->method('getJournal')
             ->with('foo@bar.com')
             ->will($this->returnValue($data));

        $mock->expects($this->any())
             ->method('save')
             ->will($this->returnValue($data));

        return $mock;
    }

    protected function getServiceFormMock()
    {
        $mock = $this->getMockBuilder('Acquisition\ServiceForm')
                     ->disableOriginalConstructor()
                     ->getMock();

        $mock->expects($this->any())
             ->method('getForm')
             ->will($this->returnValue($this->getFormMock(true)));

        $mock->expects($this->any())
             ->method('getTypes')
             ->will($this->returnValue($this->types));

        return $mock;
    }

    protected function getFormMock($boolean, $messages=null)
    {

        $form = $this->getMockBuilder('Zend\Form\Form')
                     ->disableOriginalConstructor()
                     ->getMock();

        $inputFilterMock = $this->getMockBuilder('Zend\InputFilter\InputFilterInterface')
                                ->disableOriginalConstructor()
                                ->getMock();

        $inputFilterMock->expects($this->any())
                        ->method('getMessages')
                        ->will($this->returnValue($messages));

        $form->expects($this->any())
             ->method('getInputFilter')
             ->will($this->returnValue($inputFilterMock));

        $form->expects($this->any())
             ->method('setData');

        $form->expects($this->any())
             ->method('isValid')
             ->will($this->returnValue($boolean));

        return $form;
    }

    protected function getServiceFormMockFalseCase($messages)
    {
        $mock = $this->getMockBuilder('Acquisition\ServiceForm')
                     ->disableOriginalConstructor()
                     ->getMock();
        $mock->expects($this->any())
             ->method('getForm')
             ->will($this->returnValue($this->getFormMock(false, $messages)));
        $mock->expects($this->any())
             ->method('getTypes')
             ->will($this->returnValue($this->types));

        return $mock;
    }

    protected function getFormNotValidMock()
    {
        $form = $this->getMockBuilder('Zend\Form\Form')
                     ->disableOriginalConstructor()
                     ->getMock();

        $form->expects($this->any())
             ->method('setData');

        $form->expects($this->any())
             ->method('getInputFilter')
             ->will($this->returnValue(array('email_found' => '')));

        $form->expects($this->any())
             ->method('isValid')
             ->will($this->returnValue(false));

        return $form;
    }

}
