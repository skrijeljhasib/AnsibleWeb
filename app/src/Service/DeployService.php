<?php

namespace Project\Service;

use Project\Entity\JSON\PlayBook;

class DeployService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get,$url,$project)
    {
	$ip = $app_get->get('ip');
	$contents = file_get_contents($url . '/repo/deploy_project/deploy_project.json');
	$contents = str_replace("{{{ HOSTIP }}}",$ip,$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
        return $contents;
    }
}
