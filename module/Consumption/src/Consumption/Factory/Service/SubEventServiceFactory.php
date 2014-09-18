<?php

namespace Consumption\Factory\Service;

use Consumption\SubEventService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SubEventServiceFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Consumption\SubEventService
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $m = new \MongoClient();
        $dbConsent = $m->consent;

        return new SubEventService($dbConsent);
    }
}
