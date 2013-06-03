<?php

namespace CKEditor;

return array(
    'assetic_configuration' => array(
        'default' => array(
            'assets' => array(
                '@ckeditor_js'
            ),
        ),
        'modules' => array(
            __NAMESPACE__ => array(
                'root_path' => __DIR__ . '/../public',
                'collections' => array(
                    'ckeditor_js' => array(
                        'assets' => array(
                            'js/ckeditor/ckeditor.js',
                            'js/ckeditor/adapters/jquery.js'
                        ),
                        'options' => array(
                            'output' => 'js/ckeditor/ckeditor.js',
                        ),
                    ),
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
