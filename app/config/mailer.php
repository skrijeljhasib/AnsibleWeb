<?php
use Fei\Service\Mailer\Package\Config\MailerParam;
use Fei\Service\Mailer\Client\Mailer;
use Fei\Service\Mailer\Package\Config\MailerAsyncTransport;
use Fei\Service\Mailer\Package\Config\MailerTransportOptions;
return [
    new MailerParam([Mailer::OPTION_BASEURL => 'http://mailer.test.flash-global.net']),
    new MailerTransportOptions([]),
];
