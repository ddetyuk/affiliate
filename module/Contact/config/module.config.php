<?php

namespace Contact;

return array(
    'rbac' => array(
        'Administrator' => array(
            'permissions' => array(
                array('name'=>'contact.view'),
                array('name'=>'contact.create'),
                array('name'=>'contact.update'),
                array('name'=>'contact.delete'),
                array('name'=>'contact.get'),
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/contact[/:action][/page/:page]',
                    'defaults' => array(
                        'controller'  => 'Contact\Controller\Index',
                        'action'      => 'add',
                    ),
                    'constraints' => array(
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'page'     => '[0-9]*',
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
    'controllers'          => array(
        'invokables' => array(
            'Contact\Controller\Index' => 'Contact\Controller\IndexController'
        ),
    ),
    'view_manager'             => array(
        'template_path_stack' => array(__DIR__ . '/../view'),
    ),
);