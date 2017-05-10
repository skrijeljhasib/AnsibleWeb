<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 10.05.17
 * Time: 15:34
 */

namespace Project\Service;


use Project\Entity\JSON\OsServerAuth;
use Project\Entity\JSON\OsServerFacts;
use Project\Entity\JSON\PlayBook;

class GetAllMachineService
{
    public function load($machine_template,$openstack_auth)
    {
        $playbook = new PlayBook();

        $playbook->setName('Get all machines');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('true');

        $os_server_facts = new OsServerFacts();
        $os_server_auth = new OsServerAuth();
        $os_server_auth->setAuthFromConfigFile($openstack_auth);
        $os_server_facts->setRegionName($machine_template["region_name"]);
        $os_server_facts->setAuth($os_server_auth->toArray());

        $playbook->setTask($os_server_facts->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}