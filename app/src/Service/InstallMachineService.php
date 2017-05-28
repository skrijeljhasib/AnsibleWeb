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

class InstallMachineService
{
    /**
     * @param $openstack_auth array
     * @param $machine_template array
     * @param $host array
     * @param $env string
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($openstack_auth, $machine_template, $host, $env, $app_get)
    {
        switch ($host['host_config']) {
            case 'RANDOM':
                $name = substr(md5(microtime()), rand(0, 26), 15).'.'.$env;
                $machine_template['name'] = $name;
                break;
            case 'FIXED':
                $machine_template['name'] = $machine_template['name'].'.'.$env;
                ;
                break;
            case 'CUSTOM':
                $host = json_decode($app_get->get('host'));
                foreach ($host as $key => $value) {
                    $machine_template[$key] = $value;
                    if ($key === 'name') {
                        $machine_template['name'] = $value.'.'.$env;
                    }
                }
                break;
            default:
                $name = substr(md5(microtime()), rand(0, 26), 15).'.'.$env;
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
        $lineinfile_tmp->setPath('/tmp/'.$app_get->get('tmp_file'));
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

	$jobs_gateway = $app->getServicesFactory()->get('gateway.jobs');
        $jobs = new Jobs();
        $jobs->setName('CreateMachine'.$machine_template['name']);
        $jobs->setStatus(0);
	$jobs->setJson($playbook_json);
        $jobs->setTube('installmachine');
        $jobs_gateway->put($jobs);

	$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = new Host();
        $host->setName($machine_template['name']);
        $host->setLocation($location);
        $host->setStatus('CREATING');
        $hosts_gateway->put($host);

        return $playbook_json;
    }
}
