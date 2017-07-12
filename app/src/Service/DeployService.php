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
	$contents = file_get_contents($url . '/repo/deploy_project/deploy_project.json');
	$contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
	$contents = str_replace("{{{ NAME }}}",$app_get->get('name'),$contents);
        return $contents;
    }
}
