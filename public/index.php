<?php

    use Project\Application;

    $autoloader = require '../vendor/autoload.php';

    chdir(__DIR__ . '/..');

    $app = new Application($autoloader);

    $app->setEnv(getenv('APPLICATION_ENV') ?: 'test');

    $app->loadConfig('app/config');

    $app->run();
