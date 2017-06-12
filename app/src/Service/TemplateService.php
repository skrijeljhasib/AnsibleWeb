<?php

namespace Project\Service;

use Project\Entity\JSON\PlayBook;

class TemplateService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get)
    {
	$ip = $app_get->get('ip');
	$contents = file_get_contents('http://stackstorm.test.flash-global.net:8888/repo/template.json');
	$contents = str_replace("{{{ HOSTIP }}}",$ip,$contents);
        return $contents;
    }
}
