<?php

namespace Acquisition\Factory\Controller;

use Acquisition\Controller\RestController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RestControllerFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Acquisition\Factory\RestController
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $serviceLocator = $serviceManager->getServiceLocator();

        $journalManager = $serviceLocator->get('journal');
        $serviceForm = $serviceLocator->get('ServiceForm');

        return new RestController($journalManager, $serviceForm);
    }

}
