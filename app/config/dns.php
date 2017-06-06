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
	"enabled" => "true",
    "domain" => [
        "flash-global.net",
        "vehbo.ovh"
    ],
	"type" => "A",
	"checked" => "true",
];

return [
    new OvhDnsAuth($ovh_dns_auth),
    new DnsConfig($dns_config)
];
