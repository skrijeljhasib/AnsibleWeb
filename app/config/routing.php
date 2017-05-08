<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\CreateMachine;
use Project\Action\PlayBook;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class,FastRoute::GET),
        new FastRoute('createMachine', '/createMachine', CreateMachine::class,FastRoute::GET),
        new FastRoute('playBookJSON', '/playBookJSON', PlayBook::class,FastRoute::GET),
];