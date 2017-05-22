<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\CreateMachine;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class,FastRoute::GET),
        new FastRoute('createMachine', '/createMachine', CreateMachine::class,FastRoute::GET),
];