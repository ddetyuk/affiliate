<?php

namespace Application;

return array(
    'rbac' => array(
        'Administrator' => array(
            'permissions' => array(
                array('name'=>'admin.panel'),
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller'    => 'User\Controller\User',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                )
            ),
            'home' => array(
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'page'       => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/:action[/:id]',
                    'constraints' => array(
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'mailer'        => array(),
    //admin menu
    'navigation' => array(
        'admin-menu' => array(
            'Pages' => array(
                'label' => 'User\'s Pages',
                'route' => 'user-pages',
            ),
            'Users' => array(
                'label'    => 'Users',
                'route'    => 'admin/user',
            ),
            'Messages' => array(
                'label'  => 'Messages',
                'route'  => 'contact',
                'params' => array('action'   => 'index')
            ),
            'Payments' => array(
                'label'    => 'Payments',
                'route'    => 'admin/payments',
            ),
            'Settings' => array(
                'label'      => 'Settings',
                'route'      => 'settings',
            ),
        ),
        'guest-menu' => array(
            'Home' => array(
                'label'        => 'Home',
                'route'        => 'home',
            ),
            'How it works' => array(
                'label'  => 'How it works',
                'route'  => 'page',
                'params' => array('action'   => 'how-it-works'),
            ),
            'About us' => array(
                'label'  => 'About us',
                'route'  => 'page',
                'params' => array('action' => 'about'),
            ),
            'Login'  => array(
                'label'  => 'Login',
                'route'  => 'user',
                'params' => array('action'   => 'login'),
            ),
            'Register' => array(
                'label'  => 'Register',
                'route'  => 'user',
                'params' => array('action'    => 'register'),
            ),
        ),
        'user-menu' => array(
            'Welcome' => array(
                'label'  => 'Welcome',
                'route'  => 'page',
                'params' => array('action'       => 'wellcome'),
            ),
            'Step by Step' => array(
                'label'  => 'Step by Step',
                'route'  => 'page',
                'params' => array('action' => 'step-by-step'),
                'pages'  => array(
                    array(
                        'label'  => 'Step 1',
                        'route'  => 'page',
                        'params' => array('action' => ''),
                    ),
                    array(
                        'label'  => 'Step 2',
                        'route'  => 'page',
                        'params' => array('action' => 'step2'),
                    ),
                    array(
                        'label'  => 'Step 3',
                        'route'  => 'page',
                        'params' => array('action'         => 'step3'),
                    )
                )
            ),
            'Rate of Return' => array(
                'label'  => 'Rate of Return',
                'route'  => 'page',
                'params' => array('action' => 'set-the-rate'),
                'pages'  => array(
                    array(
                        'label'  => 'Rate of Return',
                        'route'  => 'page',
                        'params' => array('action' => 'set-the-rate'),
                    ),
                    array(
                        'label'  => 'Purchase the Rate',
                        'route'  => 'page',
                        'params' => array('action' => 'purchase-the-rate'),
                    ),
                    array(
                        'label'  => 'Balance',
                        'route'  => 'page',
                        'params' => array('action'     => 'balance'),
                    )
                )
            ),
            'Contact Us' => array(
                'label'   => 'Contact Us',
                'route'   => 'contact',
            ),
            'Profile' => array(
                'label'  => 'Profile',
                'route'  => 'user',
                'params' => array('action' => 'profile'),
            ),
            'Logout' => array(
                'label'  => 'Logout',
                'route'  => 'user',
                'params' => array('action'          => 'logout'),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories'  => array(
            'Application\Service\GuestNavigationFactory' => 'Application\Service\GuestNavigationFactory',
            'Application\Service\UserNavigationFactory'  => 'Application\Service\UserNavigationFactory',
            'Application\Service\AdminNavigationFactory' => 'Application\Service\AdminNavigationFactory',
        )
    ),
    'translator'                                 => array(
        'locale'                    => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'mapping_driver' => array(
                'class'       => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache'       => 'filesystem',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model\Entity' => 'mapping_driver'
                )
            )
        )
    ),
    'controllers'              => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager'                 => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
            'ViewFeedStrategy',
        ),
        'template_path_stack'     => array(
            __DIR__ . '/../view',
        ),
    ),
    'assetic_configuration' => array(
        'buildOnRequest' => true,
        'debug'          => true,
        'default'        => array(
            'assets' => array(
                '@base_css',
                '@base_js',
                '@slider_css',
                '@slider_js',
            ),
            'options' => array(
                'mixin'   => true
            ),
        ),
        'modules' => array(
            'application' => array(
                'root_path'   => __DIR__ . '/../public',
                'collections' => array(
                    'base_css' => array(
                        'assets' => array(
                            'css/bootstrap.css',
                            'css/style.css',
                            'css/icons.css',
                            'css/bootstrap-responsive.css',
                            'js/tagsinput/*.css',
                            'js/markdown/css/*.css',
                        ),
                        'filters' => array(
                            'CssRewriteFilter' => array(
                                'name'    => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                        'options' => array(),
                    ),
                    'base_js' => array(
                        'assets' => array(
                            'js/jquery.min.js',
                            'js/bootstrap.min.js',
                            'js/module.js',
                            'js/tagsinput/*.js',
                            'js/markdown/js/*.js',
                        )
                    ),
                    'base_images' => array(
                        'assets' => array(
                            'img/*.png',
                            'img/*.jpg',
                            'img/*.ico',
                            'img/*/*.png',
                            'img/*/*.jpg',
                            'img/*/*.ico',
                            'img/*/*/*.png',
                            'img/*/*/*.jpg',
                            'img/*/*/*.ico',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        )
                    ),
                ),
            ),
            'slider'   => array(
                'root_path'   => __DIR__ . '/../public',
                'collections' => array(
                    'slider_img' => array(
                        'assets' => array(
                            'slider/img/*.*',
                            'slider/css/fonts/*.*',
                        ),
                        'options' => array(
                            'move_raw'  => true,
                        )
                    ),
                    'slider_js' => array(
                        'assets' => array(
                            'slider/js/*.js',
                        )
                    ),
                    'slider_css' => array(
                        'assets' => array(
                            'slider/css/*.css',
                        ),
                        'filters' => array(
                            'CssRewriteFilter' => array(
                                'name'    => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                        'options' => array(),
                    ),
                ),
            ),
        )
    ),
);
