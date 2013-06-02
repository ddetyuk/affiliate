<?php

namespace Account;

return array(
    'doctrine' => array(
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
);
