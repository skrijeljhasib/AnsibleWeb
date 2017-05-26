<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 25.04.17
 * Time: 17:23
 */

use ObjectivePHP\Package\Doctrine\Config\EntityManager;

return [
    new EntityManager('default', [
        'entities.locations' => ['app/src/Entity'],
        'driver'        => 'pdo_mysql',
        'host'          => '127.0.0.1',
        'port'          => 3306,
        'user'          => 'user',
        'password'      => 'password!',
        'dbname'        => 'dbname',
        'mapping_types' => [
            'enum' => 'string'
        ]
    ])
];
