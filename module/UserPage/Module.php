<?php

namespace UserPage;

use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $app    = $e->getApplication();
        $sm     = $app->getServiceManager();
        $events = $app->getEventManager();
        $events->attachAggregate($sm->get('UserPage\Listener\CreateUser'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'UserPage\Service\Page' => function($sm) {
                    $em      = $sm->get('Doctrine\ORM\EntityManager');
                    $service = new \UserPage\Service\Page($em);
                    return $service;
                },
                'UserPage\Listener\CreateUser' => function($sm) {
                    $service = $sm->get('UserPage\Service\Page');
                    return new \UserPage\Listener\CreateUser($service);
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
