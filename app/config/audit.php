<?php
use Fei\Service\Audit\Client\Audit;
use Fei\Service\Audit\Package\Config\AuditParam;
return [
    new AuditParam([Audit::OPTION_BASEURL => 'http://audit.test.flash-global.net']),
];
