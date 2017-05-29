<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Entity\JSON\LineInFile;
use Project\Entity\JSON\OsServer;
use Project\Entity\JSON\OsServerAuth;
use Project\Entity\JSON\PlayBook;
use Project\Entity\DB\Host;
use Project\Entity\DB\Jobs;

class DeleteMachineService
{
    /**
     * @param $openstack_auth array
     * @param $name string
     * @param $location string
     * @return string
     */
    public function load($openstack_auth, $name, $location, $app)
    {
        $playbook = new PlayBook();

        $playbook->setName('Delete a Machine');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $os_server = new OsServer();
        $os_server->setState('absent');
        $os_server->setWait('true');
	$os_server->setRegionName($location);
	$os_server->setOSName($name);
        $os_server_auth = new OsServerAuth();
        $os_server_auth->setAuthFromConfigFile($openstack_auth);
        $os_server->setAuth($os_server_auth->toArray());

        $playbook->setTask($os_server->toArray());

        $playbook_json = $playbook->toJSON();

        $jobs_gateway = $app->getServicesFactory()->get('gateway.jobs');
        $jobs = new Jobs();
        $jobs->setName('DeleteMachine'.$name);
        $jobs->setStatus(0);
        $jobs->setJson($playbook_json);
        $jobs->setTube('deletemachine');
        $jobs_gateway->put($jobs);

	$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
	$host = $hosts_gateway->fetchByName($name);
	$host->setStatus('DELETING');
        $hosts_gateway->put($host);
	//$hosts_gateway->delete($host);

        return $playbook_json;
    }
}
