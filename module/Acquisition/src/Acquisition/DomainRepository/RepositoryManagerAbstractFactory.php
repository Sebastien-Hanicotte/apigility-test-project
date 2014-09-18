<?php

namespace Acquisition\DomainRepository;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RepositoryManagerAbstractFactory
 */
class RepositoryManagerAbstractFactory implements AbstractFactoryInterface
{
    /**
     *
     * @var \MongoClient
     */
    protected $dbTest;

    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @param  string                                       $name
     * @param  string                                       $requestedName
     * @return boolean
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocatorInterface, $name, $requestedName)
    {
        $m = new \MongoClient();
        $this->dbTest = $m->test;
        $listCollection = $this->dbTest->getCollectionNames();

        return in_array($requestedName, $listCollection);
    }

    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface    $serviceLocator
     * @param  string                                          $name
     * @param  string                                          $requestedName
     * @return \Acquisition\DomainRepository\RepositoryManager
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $collection = $this->dbTest->{$requestedName};

        $repositoryManager = new RepositoryManager($collection);

        return $repositoryManager;
    }

    /**
     * getter return dbTest
     * @return \MongoClient
     */
    public function getDbTest()
    {
        return $this->dbTest;
    }
}
