<?php

use ObjectivePHP\ServicesFactory\Config\Service;
use ObjectivePHP\Matcher\Matcher;
use ObjectivePHP\ServicesFactory\ServiceReference;
use Project\Gateway\OrderGateway;
use Project\Gateway\PackageGateway;
use Project\Gateway\HostGateway;
use Project\Gateway\JobGateway;

/**
     * Declare your services specifications here
     */

    return [
        // Example service declaration
        //
        // call $servicesFactory->get('matcher') to build an instance of Matcher
        new Service([
            'id' => 'matcher',
            'class' => Matcher::class
        ]),
        new Service([
            'id' => 'gateway.packages',
            'class' => PackageGateway::class,
            'setters' => [
                'setEntityManager' => [new ServiceReference('doctrine.em.default')],
            ]
        ]),
        new Service([
            'id' => 'gateway.hosts',
            'class' => HostGateway::class,
            'setters' => [
                'setEntityManager' => [new ServiceReference('doctrine.em.default')],
            ]
        ]),
        new Service([
            'id' => 'gateway.jobs',
            'class' => JobGateway::class,
            'setters' => [
                'setEntityManager' => [new ServiceReference('doctrine.em.default')],
            ]
        ]),
        new Service([
            'id' => 'gateway.orders',
            'class' => OrderGateway::class,
            'setters' => [
                'setEntityManager' => [new ServiceReference('doctrine.em.default')],
            ]
        ])
    ];
