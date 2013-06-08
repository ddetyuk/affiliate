<?php
return array(
    'config_cache_enabled' => true,
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'dbname'   => 'affiliate',
                )
            )
        ),
    ),
    'assetic_configuration' => array(
        'debug' => false,
        'buildOnRequest' => false,
        'baseUrl' => null,
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'enabled' => false,
        ),
        'toolbar' => array(
            'enabled' => false,
        )
    ),
    'nette_debug' => array(
        'enabled'      => false,
        'mode'         => true,
    ),
);

