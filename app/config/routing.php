<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\CreateMachine;
use Project\Application;
use Project\Gateway\PlayBookGateway;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class,FastRoute::GET),
        new FastRoute('createMachine', '/createMachine', CreateMachine::class,FastRoute::GET),
        new FastRoute('playBookJSON', '/playBookJSON', PlayBookGateway::class,FastRoute::GET),
];