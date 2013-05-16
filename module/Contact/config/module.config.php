<?php

namespace Contact;

return array(
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/contact',
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'mapping_driver' => array(
                'paths' => array(__DIR__ . '/../src/Contact/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Contact\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Contact\Controller\Index' => 'Contact\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);