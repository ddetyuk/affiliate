<?php

namespace Account;

use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $sm = $app->getServiceManager();
        $em->attachAggregate($sm->get('Account\Service\Account'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Account\Service\Account' => function($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    $service = new \Account\Service\Account($em);
                    return $service;
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
