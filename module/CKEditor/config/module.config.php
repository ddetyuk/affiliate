<?php

return array(
    'assetic_configuration' => array(
        'buildOnRequest' => true,
        'debug' => true,
        'default' => array(
            'assets' => array(
                
            ),
            'options' => array(
                'mixin' => true
            ),
        ),
        'routes' => array(
            'default' => array(
                
            ),
        ),
        'modules' => array(
            'application' => array(
                'root_path' => __DIR__ . '/../public',
                'collections' => array(
                    'ckeditor' => array(
                        'assets' => array(
                            'js/ckeditor/*.*',
                            'js/ckeditor/lang/*.*',
                            'js/ckeditor/skins/moono/*.*',
                            'js/ckeditor/skins/moono/images/*.*',
                            'js/ckeditor/plugins/*.*',
                            'js/ckeditor/plugins/*/*.*',
                            'js/ckeditor/plugins/*/*/*.*',
                            'js/ckeditor/plugins/*/*/*/*.*',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        )
                    ),
                ),
            ),
        )
    )
);
