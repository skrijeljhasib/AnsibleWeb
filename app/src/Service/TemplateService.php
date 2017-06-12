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
	$url = $app_get->get('url');
	$contents = file_get_contents($url["ansible_playbook"] . '/repo/template.json');
	$contents = str_replace("{{{ HOSTIP }}}",$ip,$contents);
        return $contents;
    }
}
