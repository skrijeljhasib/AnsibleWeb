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
	$contents = file_get_contents($url . '/repo/deploy_project/' . $project . '/deploy_project.json');
	$contents = str_replace("{{{ REPO_URL }}}",$url,$contents);
	$contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
	$contents = str_replace("{{{ NAME }}}",$app_get->get('name'),$contents);
	$contents = str_replace("{{{ MYSQL_ROOT_PWD }}}",$app_get->get('mysql_root_password'),$contents);
        return $contents;
    }
}
