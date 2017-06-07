<?php

namespace Config;

use Project\Config\Notification;


$notification = [
    "enabled" => "false",
    "type" => "email",
    "email" => "no-reply@flash-global.net"
];

return [
    new Notification($notification)
];
