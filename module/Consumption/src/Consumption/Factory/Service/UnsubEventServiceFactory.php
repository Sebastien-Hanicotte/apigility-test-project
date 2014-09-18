<?php

namespace Consumption\Factory\Service;

use Consumption\UnsubEventService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UnsubEventServiceFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Consumption\UnsubEventService
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $m = new \MongoClient();
        $dbConsent = $m->consent;

        return new UnsubEventService($dbConsent);
    }
}
