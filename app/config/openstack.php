<?php

namespace Config;

use Project\Config\Host;
use Project\Config\MachineAccess;
use Project\Config\OpenStackAuth;
use Project\Config\MachineTemplate;

$host = [
    "host_config" => "CUSTOM", // FIXED, CUSTOM, RANDOM
    "host_name" => "AnsibleWebTest" // Only used if $host_config is set to FIXED
];

$openstack_auth = [
    "auth_url" => "auth_url",
    "username" => "username",
    "password" => "password",
    "project_name" => "project_name"
];

$machine_template = [
    "name" => $host['host_name'],
    "key_name" => "ansiblekey",
    "image" => "image",
    "flavor" => "flavor",
    "auto_ip" => "auto-ip",
    "network" => "network",
    "region_name" => "region_name",
    "timeout" => "timeout"
];

$machine_access = [
    "remote_user" => "remote_user"
];


return [
    new OpenStackAuth($openstack_auth),
    new MachineTemplate($machine_template),
    new Host($host),
    new MachineAccess($machine_access)
];