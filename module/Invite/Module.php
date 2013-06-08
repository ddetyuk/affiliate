<?php

namespace Invite;

class Module
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Invite\Service\Invite' => function($sm) {
                    $em              = $sm->get('Doctrine\ORM\EntityManager');
                    $service         = new \Invite\Service\Invite($em);
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
