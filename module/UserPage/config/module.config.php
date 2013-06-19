<?php

namespace UserPage;

return array(
    'router' => array(
        'routes' => array(
            'wellcome' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/wellcome[/:id]',
                    'constraints' => array(
                        'id'   => '[0-9]*',
                    ),
                    'defaults' => array(
                        'id'         => '',
                        'controller' => 'UserPage\Controller\Index',
                        'action'     => 'wellcome',
                    ),
                ),
            ),
            'user-pages' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/user-page/:action',
                    'constraints' => array(
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'UserPage\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'doctrine'   => array(
        'driver' => array(
            'mapping_driver' => array(
                'paths' => array(__DIR__ . '/../src/UserPage/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'UserPage\Model\Entity' => 'mapping_driver'
                )
            )
        ),
    ),
    'controllers'           => array(
        'invokables' => array(
            'UserPage\Controller\Page'  => 'UserPage\Controller\PageController',
            'UserPage\Controller\Index' => 'UserPage\Controller\IndexController',
        ),
    ),
    'view_manager'              => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);
