<?php

namespace Account;

return array(
    'rbac' => array(
        'Administrator' => array(
            'permissions' => array(
                array('name'=>'account.payment.view'),
                array('name'=>'account.setting.update'),
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'settings' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/settings',
                    'defaults' => array(
                        'controller' => 'Account\Controller\Setting',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin'      => array(
                'child_routes' => array(
                    'payments' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/payments/:action',
                            'constraints' => array(
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Account\Controller\Payment',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                )
            ),
        ),
    ),
    'doctrine'   => array(
        'driver' => array(
            'mapping_driver' => array(
                'paths' => array(__DIR__ . '/../src/Account/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Account\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
    'controllers'          => array(
        'invokables' => array(
            'Account\Controller\Setting' => 'Account\Controller\SettingController',
            'Account\Controller\Payment' => 'Account\Controller\PaymentController',
        ),
    ),
    'view_manager'               => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);
