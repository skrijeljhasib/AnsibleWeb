<?php

namespace Config;

use Project\Config\Monitoring;


$monitoring = [
    	"enabled" => "true",
    	"munin" => "true",
	"munin_server" => "localhost",
	"munin_ansible" => "repo/munin.json",
	"nagios" => "true",
	"nagios_server" => "localhost",
	"nagios_ansible" => "repo/nagios.json"
];

return [
    new Monitoring($monitoring)
];
