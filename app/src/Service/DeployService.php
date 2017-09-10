<?php

namespace Project\Service;

use Project\Entity\PlayBook;
use Project\Entity\Host;
use Project\Entity\Services;

class DeployService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app,$url,$project)
    {
	$app_get = $app->getRequest()->getParameters();
	$services_gateway = $app->getServicesFactory()->get('gateway.services');
        //$service = $services_gateway->fetchByNameAndService($app_get->get('name'),$project);
	$service = $services_gateway->fetchByName($app_get->get('name'));

	$contents = file_get_contents($url . '/repo/deploy_project/' . $project . '/deploy_project.json');
	$contents = str_replace("{{{ REPO_URL }}}",$url,$contents);
	$contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
	$contents = str_replace("{{{ NAME }}}",$app_get->get('name'),$contents);
	$contents = str_replace("{{{ MYSQL_ROOT_PWD }}}",$app_get->get('mysql_root_password'),$contents);
        //$contents = str_replace("{{{ ORIGIN }}}",'v1.0.3',$contents);

        if (!$service) {
		$service = new Services();
		$service->setName($app_get->get('name'));
	}

	$service->setService($project);
        $services_gateway->put($service);
        return $contents;
    }

    public function loadagain($app,$url,$project)
    {
        $app_get = $app->getRequest()->getParameters();

        $contents = file_get_contents($url . '/repo/deploy_project/' . $project . '/redeploy_project.json');
        $contents = str_replace("{{{ REPO_URL }}}",$url,$contents);
        $contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
        $contents = str_replace("{{{ PROJECT }}}",$project,$contents);
        $contents = str_replace("{{{ NAME }}}",$app_get->get('name'),$contents);

        return $contents;
    }


    public function dns($app,$url,$project)
    {
	$app_get = $app->getRequest()->getParameters();
	$contents = file_get_contents($url . '/repo/deploy_project/' . $project . '/dns.json');
	$contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
	$contents = str_replace("{{{ PROJECT }}}",$project,$contents);
        $contents = str_replace("{{{ NAME }}}",$app_get->get('name'),$contents);
 	return $contents;
    }
}
