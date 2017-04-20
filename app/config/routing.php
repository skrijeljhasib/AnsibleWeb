<?php

use ObjectivePHP\Package\FastRoute\Config\FastRoute;
use Project\Action\Home;
use Project\Action\InstallServer;

return [
        // route aliasing
        new FastRoute('home', '/', Home::class),
        new FastRoute('installServer', '/installServer', InstallServer::class),
        new FastRoute('post_data','/post_data', function() {
            $ch = curl_init('http://localhost:8080/post_data');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(file_get_contents('php://input'))
                )
            );

            echo curl_exec($ch);
        },FastRoute::POST),
];