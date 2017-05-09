<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Entity\JSON\LineInFile;
use Project\Entity\JSON\OsServer;
use Project\Entity\JSON\OsServerAuth;
use Project\Entity\JSON\PlayBook;

class MachineService
{
    public function load($ansible_api, $openstack_auth, $machine_template, $host, $env, $getHost)
    {
        switch ($host['host_config'])
        {
            case 'RANDOM':
                $name = substr(md5(microtime()),rand(0,26),15).'.'.$env;
                $machine_template['name'] = $name;
                break;
            case 'FIXED':
                $name = $host['host_name'].'.'.$env;
                $machine_template['name'] = $name;
                break;
            case 'CUSTOM':
                $host = json_decode($getHost);
                foreach ($host as $key => $value)
                {
                    $machine_template[$key] = $value;
                    if($key === 'name') {
                        $machine_template['name'] = $value.'.'.$env;
                    }
                }
                break;
            default:
                $name = substr(md5(microtime()),rand(0,26),15).'.'.$env;
                $machine_template['name'] = $name;
        }

        $playbook = new PlayBook();

        $playbook->setName('Create and install a Machine');
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

        $lineinfile_tmp = new LineInFile();
        $lineinfile_tmp->setPath($ansible_api['tmp_file']);
        $lineinfile_tmp->setCreate('yes');
        $lineinfile_tmp->setLine('{{ '.$os_server->getRegister().'.server.public_v4 }}');

        $lineinfile_inventory = new LineInFile();
        $lineinfile_inventory->setPath('{{ inventory_file }}');
        $lineinfile_inventory->setCreate('yes');
        $lineinfile_inventory->setLine('{{ '.$os_server->getRegister().'.server.public_v4 }}');

        $playbook->setTask($os_server->toArray());
        $playbook->setPostTask($lineinfile_tmp->toArray());
        $playbook->setPostTask($lineinfile_inventory->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}