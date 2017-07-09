<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\JSON\OsServer;
use Project\Entity\JSON\OsServerAuth;
use Project\Entity\JSON\PlayBook;

class DeleteMachineService
{
    /**
     * @param $openstack_auth array
     * @param $app Application
     * @return string
     */
    public function load($openstack_auth, $app)
    {
        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = $hosts_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));

        $playbook = new PlayBook();

        $playbook->setName('Delete '. $host->getName());
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $os_server = new OsServer();
        $os_server->setState('absent');
        $os_server->setWait('true');
        $os_server->setRegionName($host->getLocation());
        $os_server->setOSName($host->getName());
        $os_server_auth = new OsServerAuth();
        $os_server_auth->setAuthFromConfigFile($openstack_auth);
        $os_server->setAuth($os_server_auth->toArray());

        $playbook->setTask($os_server->toArray());

        $playbook_json = $playbook->toJSON();

        $host->setStatus('DELETING');
        $hosts_gateway->put($host);

        $orders_gateway = $app->getServicesFactory()->get('gateway.orders');
        $order = $orders_gateway->fetchByName($host->getName());
        if ($order) { $orders_gateway->delete($order); }

        return $playbook_json;
    }
}
