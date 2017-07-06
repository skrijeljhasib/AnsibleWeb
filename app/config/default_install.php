<?php

namespace Config;

use Project\Config\DefaultInstall;

$default_install = [
	"apache2" => "true",
	"mysql"	=> "true",
	"php" => "true"
];

return [
	new DefaultInstall($default_install)
];
