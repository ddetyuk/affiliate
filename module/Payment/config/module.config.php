<?php

namespace Payment;

return array(
    'gateway' => array(
        'payza' => array(
            'options' => array(
                //in config/local.php
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Payment\Controller\Index' => 'Payment\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'payment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/payment[/:action]',
                    'constraints' => array(
                        'keyword' => '[a-zA-Z0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Payment\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __NAMESPACE__ => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'PaymentButton' => 'Payment\View\Helper\PaymentButton',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'mapping_driver' => array(
                'paths' => array(__DIR__ . '/../src/Payment/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Payment\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
);
