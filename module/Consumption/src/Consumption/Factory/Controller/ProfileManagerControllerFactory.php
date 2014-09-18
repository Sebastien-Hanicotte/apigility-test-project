<?php

namespace Consumption\Factory\Controller;

use Consumption\Controller\ProfileManagerController;
use Consumption\EventService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception;

class ProfileManagerControllerFactory implements FactoryInterface
{
    /**
     * Create a ProfilManager.
     *
     * @param  ServiceLocatorInterface              $serviceManager
     * @return Consumption\Entity\JournalManager
     * @throws Exception\ServiceNotCreatedException
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $serviceLocator = $serviceManager->getServiceLocator();
        $journalManager = $serviceLocator->get('journal');
        $eventService   = new EventService($serviceLocator);

        return new ProfileManagerController($journalManager, $eventService);
    }

}
