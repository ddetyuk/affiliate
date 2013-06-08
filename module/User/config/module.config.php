<?php

namespace User;

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/user/:action',
                    'constraints' => array(
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'login',
                    ),
                ),
            ),
            'admin'      => array(
                'child_routes' => array(
                    'user' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/user/:action',
                            'constraints' => array(
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'User\Controller\User',
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
                'paths' => array(__DIR__ . '/../src/User/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
    'service_manager'   => array(
        'factories' => array(
            'User\Form\Login'    => 'User\Form\Login',
            'User\Form\Register' => 'User\Form\Register',
            'User\Form\Profile'  => 'User\Form\Profile',
        ),
    ),
    'controllers'        => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\User'  => 'User\Controller\UserController',
        ),
    ),
    'controller_plugins'    => array(
        'invokables' => array(
            'isGranted'    => 'User\Controller\Plugin\IsGranted',
            'getUser'      => 'User\Controller\Plugin\GetUser',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'IsGranted'    => 'User\View\Helper\IsGranted',
            'HasIdentity'  => 'User\View\Helper\HasIdentity',
            'GetUser'      => 'User\View\Helper\GetUser',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'roles'   => 'User\Collector\RoleCollector',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'roles' => 'zend-developer-tools/toolbar/roles',
            ),
        ),
    ),
);
