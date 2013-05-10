<?php

namespace User;

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user/:action',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action' => 'login',
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'annotation_driver' => array(
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'annotation_driver'
                )
            )
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'User\Form\Login' => 'User\Form\Login',
            'User\Form\Register' => 'User\Form\Register',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'roles' => 'User\Collector\RoleCollector',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'roles' => 'zend-developer-tools/toolbar/roles',
            ),
        ),
    ),
);
