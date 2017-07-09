<?php

namespace Config;

use Project\Config\DefaultInstall;

$default_install = [
	"apache2" => "true",
	"mysql"	=> "true",
	"mysql_root_pwd" => 'toto',
	"php" => "true"
];

return [
	new DefaultInstall($default_install)
];
