<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\Order;
use Project\Entity\Host;

class InstallMachineService
{
    /**
     * @param $openstack_auth array
     * @param $machine_template array
     * @param $host array
     * @param $app Application
     * @return string
     */
    public function load($openstack_auth, $machine_template, $host, $app, $url)
    {
        $app_get = $app->getRequest()->getParameters();
        switch ($host['host_config']) {
            case 'RANDOM':
                $name = substr(md5(microtime()), rand(0, 26), 15);
                $machine_template['name'] = $name;
                break;
            case 'CUSTOM':
                $host = json_decode($app_get->get('host'));
                foreach ($host as $key => $value) {
                    $machine_template[$key] = $value;
                    if ($key === 'name') {
                        $machine_template['name'] = $value;
                    }
                }
                break;
            default:
                $name = substr(md5(microtime()), rand(0, 26), 15);
                $machine_template['name'] = $name;
        }
        
	$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
	$host = $hosts_gateway->fetchByName($machine_template['name']);
	if ($host) { exit; }

        $contents = file_get_contents($url . '/repo/machine_install/install.json');
        $contents = str_replace("{{{ AUTH_URL }}}",$openstack_auth['auth_url'],$contents);
        $contents = str_replace("{{{ AUTH_USERNAME }}}",$openstack_auth['username'],$contents);
        $contents = str_replace("{{{ AUTH_PASSWORD }}}",$openstack_auth['password'],$contents);
        $contents = str_replace("{{{ AUTH_PROJECT }}}",$openstack_auth['project_name'],$contents);
        $contents = str_replace("{{{ HOST_REGION }}}",$machine_template['region_name'],$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$machine_template['name'],$contents);

        $orders_gateway = $app->getServicesFactory()->get('gateway.orders');
        $order = new Order();
        $order->setName($machine_template['name']);

        if(!is_null($app->getRequest()->getParameters()->get('packages'))) {
            $order->setPackages($app->getRequest()->getParameters()->get('packages'));
        }
        if(!is_null($app->getRequest()->getParameters()->get('webserver'))) {
            $order->setWebserver($app->getRequest()->getParameters()->get('webserver'));
        }
        if(!is_null($app->getRequest()->getParameters()->get('database'))) {
            $order->setDatabase($app->getRequest()->getParameters()->get('database'));
        }
        if(!is_null($app->getRequest()->getParameters()->get('language'))) {
            $order->setLanguage($app->getRequest()->getParameters()->get('language'));
        }
        if(!is_null($app->getRequest()->getParameters()->get('dns'))) {
            $order->setDns($app->getRequest()->getParameters()->get('dns'));
        }
	if(!is_null($app->getRequest()->getParameters()->get('templatejson'))) {
            $order->setTemplateJson($app->getRequest()->getParameters()->get('templatejson'));
        }
        if(!is_null($app->getRequest()->getParameters()->get('project'))) {
            $order->setDeploy($app->getRequest()->getParameters()->get('project'));
        }
        $orders_gateway->put($order);

        $host = new Host();
        $host->setName($machine_template['name']);
        $host->setLocation($machine_template['region_name']);
	$key = "dns_domain_name";
	$value = json_decode($app->getRequest()->getParameters()->get('dns'))->$key;
	$host->setDomain($value);
	$host->setOwnerId($app->getServicesFactory()->get('connect.client')->getUser()->getId());
	$host->setHostID('');
        $host->setStatus('CREATING');
        $hosts_gateway->put($host);

        return $contents;
    }
}
