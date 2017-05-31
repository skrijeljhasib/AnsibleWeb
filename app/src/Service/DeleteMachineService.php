<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\DB\Job;
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
        $playbook = new PlayBook();

        $playbook->setName('Delete a Machine');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = $hosts_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));

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

        $jobs_gateway = $app->getServicesFactory()->get('gateway.jobs');
        $jobs = new Job();
        $jobs->setName('DeleteMachine' . $host->getName());
        $jobs->setStatus(0);
        $jobs->setJson($playbook_json);
        $jobs->setTube('deletemachine');
        $jobs_gateway->put($jobs);

        $host->setStatus('DELETING');
        $hosts_gateway->put($host);

        return $playbook_json;
    }
}
