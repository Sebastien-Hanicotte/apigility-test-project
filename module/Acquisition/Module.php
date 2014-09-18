<?php
namespace Acquisition;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

//use Zend\ModuleManager\Feature\BootstrapListenerInterface;
//
//use Zend\EventManager\EventInterface;
//
//use Doctrine\MongoDB\Connection;
//use Doctrine\ODM\MongoDB\Configuration;
//use Doctrine\ODM\MongoDB\DocumentManager;
//use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

class Module implements \ZF\Apigility\Provider\ApigilityProviderInterface,AutoloaderProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
//
//    public function onBoostrap(EventInterface $e) {
//        if ( ! file_exists($file = __DIR__.'/vendor/autoload.php')) {
//            throw new RuntimeException('Install dependencies to run this script.');
//        }
//
//        $loader = require_once $file;
//        $loader->add('Documents', __DIR__);
//
//        $connection = new Connection();
//
//        $config = new Configuration();
//        $config->setProxyDir(__DIR__ . '/Proxies');
//        $config->setProxyNamespace('Proxies');
//        $config->setHydratorDir(__DIR__ . '/Hydrators');
//        $config->setHydratorNamespace('Hydrators');
//        $config->setDefaultDB('doctrine_odm');
//        $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/Documents'));
//
//        AnnotationDriver::registerAnnotationClasses();
//
//        $dm = DocumentManager::create($connection, $config);
//    }
}
