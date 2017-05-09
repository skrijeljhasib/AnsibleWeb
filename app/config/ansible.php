<?php

namespace Config;

use Project\Config\AnsibleApi;


$ansible_api = [
    "address" => "address",
    "tmp_file" => "/tmp/new_host.txt"
];


return [
    new AnsibleApi($ansible_api)
];