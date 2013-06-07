<?php

namespace Account;

return array(
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
            'Account\Controller\Setting' => 'Account\Controller\SettingController'
        ),
    ),
    'view_manager'               => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);
