<?php

namespace Payment;

class Module
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Payment\Gateway\Payza' => function($sm) {
                    $config     = $sm->get('Config');
                    $options = $config['gateway']['payza']['options'];
                    return new \Payment\Gateway\Payza($options);
                },
                'Payment\Service\Payment' => function($sm) {
                    $em      = $sm->get('Doctrine\ORM\EntityManager');
                    $app     = $sm->get('Application');
                    $events  = $app->getEventManager();
                    $service = new \Payment\Service\Payment($em, $events);
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
