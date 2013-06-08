<?php

namespace Invite;

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'child_routes' => array(
                    'invite' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/invite/:action',
                            'constraints' => array(
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Invite\Controller\Index',
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
                'paths' => array(__DIR__ . '/../src/Invite/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Invite\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
    'controllers'         => array(
        'invokables' => array(
            'Invite\Controller\Index' => 'Invite\Controller\IndexController',
        ),
    ),
    'view_manager'            => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);
