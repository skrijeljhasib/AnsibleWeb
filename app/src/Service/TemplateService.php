<?php

namespace Project\Service;

use Project\Entity\PlayBook;

class TemplateService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function loadinstall($app_get,$url,$templatename)
    {
	$ip = $app_get->get('ip');
	$contents = file_get_contents($url . '/repo/template_install/' . $templatename . '.json');
	$contents = str_replace("{{{ HOST_IP }}}",$ip,$contents);
        return $contents;
    }
    public function loaddelete($app_get,$url,$templatename)
    {
        $ip = $app_get->get('ip');
        $contents = file_get_contents($url . '/repo/template_delete/delete.json');
        $contents = str_replace("{{{ HOST_IP }}}",$ip,$contents);
        return $contents;
    }
}
