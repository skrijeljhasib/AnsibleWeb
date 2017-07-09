<?php

namespace Project\Service;

use Project\Entity\PlayBook;

class TemplateService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get,$url,$templatename)
    {
	$ip = $app_get->get('ip');
	$contents = file_get_contents($url . '/repo/template_install/' . $templatename . '.json');
	$contents = str_replace("{{{ HOSTIP }}}",$ip,$contents);
        return $contents;
    }
}
