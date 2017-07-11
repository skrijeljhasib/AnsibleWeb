<?php

namespace Project\Service;

use Project\Entity\PlayBook;

class DeployService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get,$url,$project)
    {
	$ip = $app_get->get('ip');
	$name = $app_get->get('name');
	$contents = file_get_contents($url . '/repo/deploy_project/deploy_project.json');
	$contents = str_replace("{{{ HOSTIP }}}",$ip,$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
	$contents = str_replace("{{{ NAME }}}",$name,$contents);
        return $contents;
    }
}
