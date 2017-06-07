<?php

namespace Config;

use Project\Config\Url;
use Project\Config\Host;
use Project\Config\MachineAccess;
use Project\Config\OpenStackAuth;
use Project\Config\MachineTemplate;

$url = [
    "ansible_api" => "http://localhost:8080",
    "ansible_api_auth" => ['username' => '','password' => ''],
    "ansible_playbook" => "http://127.0.0.1",
    "ansible_playbook_auth" => ['username' => '','password' => ''],
    "beanstalk" => "127.0.0.1",
    "websocket_server" => "ws://0.0.0.0:9000",
    "websocket_client" => "ws://127.0.0.1:9000"
];

$openstack_auth = [
    "auth_url" => "auth_url",
    "username" => "username",
    "password" => "password",
    "project_name" => "project_name"
];

$host = [
    "host_config" => "CUSTOM", // FIXED, CUSTOM, RANDOM
];

$machine_template = [
    "name" => "AnsibleWebTest",
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
    new MachineAccess($machine_access),
    new Url($url),
];
