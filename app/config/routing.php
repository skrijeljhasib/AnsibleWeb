<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\InstallServer;
use Project\Application;
use Project\Gateway\PlayBookGateway;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class,FastRoute::GET),
        new FastRoute('installServer', '/installServer', InstallServer::class,FastRoute::GET),
        new FastRoute('playBookJSON', '/playBookJSON', PlayBookGateway::class,FastRoute::GET),
];