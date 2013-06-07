<?php

namespace UserPage;

use Zend\Authentication\AuthenticationService;
use User\Service\Authentication;
use User\Service\Authorization;
use User\Collector\RoleCollector;

class Module
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
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
