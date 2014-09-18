<?php

namespace Consumption\Factory\Service;

use Consumption\BlacklistService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlacklistServiceFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Consumption\BlackListService
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $m = new \MongoClient();
        $dbTest = $m->test;
        $blackListCollection = $dbTest->blacklist;

        $mongo = new \MongoClient();
        $dbConsent = $mongo->consent;

        return new BlackListService($blackListCollection, $dbConsent);
    }
}
