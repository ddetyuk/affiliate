<?php

namespace Account;

use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();
        $events = $app->getEventManager();
        $events->attachAggregate($sm->get('Account\Service\Account'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Account\Form\Setting'    => 'Account\Form\Setting',
                'Account\Service\Account' => function($sm) {
                    $em                       = $sm->get('Doctrine\ORM\EntityManager');
                    $setting                  = $sm->get('Setting\Service\Setting');
                    $service                  = new \Account\Service\Account($em, $setting);
                    return $service;
                },
                'Setting\Service\Setting' => function($sm) {
                    $em      = $sm->get('Doctrine\ORM\EntityManager');
                    $service = new \Account\Service\Setting($em);
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
