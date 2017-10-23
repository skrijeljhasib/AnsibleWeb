<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\CreateMachine;
use Project\Action\ListMachine;
use Project\Action\GetAllMachine;
use Project\Api\Redeploy;
use Project\Api\Deploy;
use Project\Api\PlayBook;
use ObjectivePHP\Router\Config\UrlAlias;

return [
        // route aliasing
        new FastRoute('createMachine', '/createMachine', CreateMachine::class, FastRoute::GET),
        new FastRoute('listMachine', '/listMachine', ListMachine::class, FastRoute::GET),
	new FastRoute('api/redeploy', '/api/redeploy', Redeploy::class, FastRoute::GET),
	new FastRoute('api/deploy', '/api/deploy', Deploy::class, FastRoute::GET),
	new FastRoute('api/PlayBook', '/api/PlayBook', PlayBook::class, FastRoute::GET),
	new UrlAlias('/', '/listMachine')
];
