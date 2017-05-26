<?php

namespace Config;

use ObjectivePHP\Application\Config\ActionNamespace;
use ObjectivePHP\Application\Config\ApplicationName;
use ObjectivePHP\Application\Config\LayoutsLocation;

return [
    new ApplicationName('AnsibleWeb'),
    new ActionNamespace('Project\\Action'),
    new LayoutsLocation('app/layouts'),
];
