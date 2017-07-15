<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\CreateMachine;
use Project\Action\ListMachine;
use Project\Action\GetAllMachine;
use ObjectivePHP\Router\Config\UrlAlias;

return [
        // route aliasing
        new FastRoute('createMachine', '/createMachine', CreateMachine::class, FastRoute::GET),
        new FastRoute('listMachine', '/listMachine', ListMachine::class, FastRoute::GET),
	new UrlAlias('/', '/listMachine')
];
