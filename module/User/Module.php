<?php

namespace User;

use Zend\Authentication\AuthenticationService;
use User\Service\Authentication;
use User\Service\Authorization;
use User\Collector\RoleCollector;

class Module
{

    public function getServiceConfig()
    {
        return array (
            'factories' => array (
                'Zend\Authentication\AuthenticationService' => function($sm) {
                    $storage = new \User\Authentication\Storage\Session();
                    $adapter = $sm->get('User\Authentication\Adapter\User');
                    return new AuthenticationService($storage, $adapter);
                },
                'User\Service\Authentication' => function($sm) {
                    $service = $sm->get('Zend\Authentication\AuthenticationService');
                     return new Authentication($service);
                },
                'User\Service\Authorization' => function($sm) {
                    $auth = $sm->get('User\Service\Authentication');
                    $rbac = $sm->get('User\Permissions\Rbac\Rbac');
                    return new Authorization($auth, $rbac);
                },
                'User\Permissions\Rbac\Rbac' => function($sm) {
                    $config = $sm->get('Config');
                    return new \User\Permissions\Rbac\Rbac($config['rbac']);
                },
                'User\Authentication\Adapter\User' => function($sm) {
                    $user    = $sm->get('User\Service\User');
                    $adapter = new \User\Authentication\Adapter\User($user);
                    return $adapter;
                },
                'User\Service\User' => function($sm) {
                    $em      = $sm->get('Doctrine\ORM\EntityManager');
                    $app     = $sm->get('Application');
                    $events  = $app->getEventManager();
                    $service = new \User\Service\User($em, $events);
                    return $service;
                },
                'User\Service\Role' => function($sm) {
                    $em       = $sm->get('Doctrine\ORM\EntityManager');
                    $service  = new \User\Service\Role($em);
                    return $service;
                },
                'User\Collector\RoleCollector' => function($sm) {
                    $authorization = $sm->get('User\Service\Authorization');
                    return new RoleCollector($authorization);
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
        return array (
            'Zend\Loader\ClassMapAutoloader' => array (
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array (
                'namespaces' => array (
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
