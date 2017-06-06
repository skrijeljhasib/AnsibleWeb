<?php

namespace Config;

use Project\Config\OvhDnsAuth;
use Project\Config\DnsConfig;

$ovh_dns_auth = [
    "OVH_ENDPOINT" => "ovh-eu",
    "OVH_APPLICATION_KEY" => "key",
    "OVH_APPLICATION_SECRET" => "secret_key",
    "OVH_CONSUMER_KEY" => "key"
];

$dns_config = [
	"enabled" => "false",
    "domain" => [

    ],
	"type" => "A",
	"checked" => "false",
];

return [
    new OvhDnsAuth($ovh_dns_auth),
    new DnsConfig($dns_config)
];
