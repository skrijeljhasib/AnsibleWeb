<?php

namespace Config;

use Project\Config\Monitoring;


$monitoring = [
    "enabled" => "false",
    "monitoring" => "false",
    "monitoring_type" => "Munin",
    "monitoring_server" => "localhost",
    "monitoring_ansible" => "repo/monitoring.json",
    "alerting" => "false",
    "alerting_type" => "Nagios",
    "alerting_server" => "localhost",
    "alerting_link_server" => "/vshell2/#/hosts/",
    "alerting_ansible" => "repo/alerting.json"
];

return [
    new Monitoring($monitoring)
];
