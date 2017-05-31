<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\DeleteMachine;
use Project\Action\Home;
use Project\Action\CreateMachine;
use Project\Action\ListMachine;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class, FastRoute::GET),
        new FastRoute('createMachine', '/createMachine', CreateMachine::class, FastRoute::GET),
        new FastRoute('listMachine', '/listMachine', ListMachine::class, FastRoute::GET),
];
