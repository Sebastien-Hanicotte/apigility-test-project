<?php

namespace Acquisition\Factory\Service;

use Acquisition\ServiceForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceFormFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Acquisition\ServiceForm
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $m = new \MongoClient();
        $dbTest = $m->test;
        $definitionCollection = $dbTest->definition;

        try {
            $validatorManager = $serviceManager->get('ValidatorManager');
        } catch (\Exception $excetion) {
            throw new \Exception(sprintf(
                '%s cannot retrieve service ValidatorManager',
                __METHOD__
            ));
        }

        /**
         * @todo verification du type de definitionCollection et lancement d'une exception si necessaire
         */

        return new ServiceForm($definitionCollection, $validatorManager);
    }
}
