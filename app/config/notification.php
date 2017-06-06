<?php

namespace Config;

use Project\Config\Notification;


$notification = [
    "enabled" => "true",
    "type" => "email",
    "email" => "no-reply@flash-global.net"
];

return [
    new Notification($notification)
];
