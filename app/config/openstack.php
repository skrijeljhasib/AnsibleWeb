<?php

namespace Config;

use Project\Config\Host;
use Project\Config\OpenStackAuth;
use Project\Config\MachineTemplate;
use Project\Config\MachineAccess;

$host = [
    "host_config" => "FIXED", // FIXED, CUSTOM, RANDOM
    "host_name" => "Ansible.test" // Only used if $host_config is FIXED
];

$auth = [
    "auth_url" => "auth_url",
    "username" => "username",
    "password" => "password",
    "project_name" => "project_name"
];

$machine_template = [
    "name" => $host['host_name'],
    "key_name" => "key_name",
    "image" => "image",
    "flavor" => "flavor",
    "auto_ip" => "auto-ip",
    "network" => "network",
    "region_name" => "region_name",
    "meta" => [
        "hostname" => $host['host_name']
    ]
];



return [
    new OpenStackAuth($auth),
    new MachineTemplate($machine_template),
    new Host($host)
];