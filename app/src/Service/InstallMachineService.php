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
use Project\Entity\OsServer;
use Project\Entity\OsServerAuth;
use Project\Entity\PlayBook;
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
    public function load($openstack_auth, $machine_template, $host, $app)
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

        $playbook = new PlayBook();

        $playbook->setName('Install '.$machine_template['image']);
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $os_server = new OsServer();
        $os_server->setState('present');
        $os_server->setRegister('newserver');
        $os_server->setMachineFromConfigFile($machine_template);
        $os_server_auth = new OsServerAuth();
        $os_server_auth->setAuthFromConfigFile($openstack_auth);
        $os_server->setAuth($os_server_auth->toArray());

        $playbook->setTask($os_server->toArray());

        $playbook_json = $playbook->toJSON();

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

        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = new Host();
        $host->setName($machine_template['name']);
        $host->setLocation($machine_template['region_name']);
	$host->setHostID('');
        $host->setStatus('CREATING');
        $hosts_gateway->put($host);

        return $playbook_json;
    }
}
