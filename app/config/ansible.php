<?php

namespace Config;

use Project\Config\AnsibleApi;
use Project\Config\MachineAccess;

$api_ip = 'localhost:8080';


// If you change the ansible_hosts_file, you must also change it in the AnsibleApi/config/app.ini file
$machine_access = [
    "ansible_hosts_file" => "/var/www/html/AnsibleApi/config/hosts",
    "tmp_file" => "/tmp/new_host.txt",
    "remote_user" => "ubuntu"
];


return [
    new MachineAccess($machine_access),
    new AnsibleApi($api_ip)
];