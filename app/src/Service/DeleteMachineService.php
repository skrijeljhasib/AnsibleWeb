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

class DeleteMachineService
{
    /**
     * @param $openstack_auth array
     * @param $name string
     * @param $location string
     * @return string
     */
    public function load($openstack_auth, $name, $location)
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

        return $playbook_json;
    }
}
