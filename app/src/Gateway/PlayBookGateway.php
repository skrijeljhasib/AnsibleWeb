<?php

namespace Project\Gateway;

use Project\Application;
use Project\Config\Host;
use Project\Config\OpenStackAuth;
use Project\Config\MachineTemplate;
use Project\Config\MachineAccess;
use Project\Entity\PlayBook;
use stdClass;

class PlayBookGateway
{

    function __invoke(Application $app)
    {
        switch ($app->getRequest()->getParameters()->get('playbook'))
        {
            case 'machine':
                $json = $this->machine($app);
                break;
            case 'package':
                $json = $this->package($app);
                break;
            case 'wait':
                $json = $this->wait($app);
                break;
            case 'clean':
                $json = $this->clean($app);
                break;
            default:
                $json = json_encode (new stdClass);;
        }

        echo $json;
    }


    private function machine(Application $app) {
        $host = $app->getConfig()->get(Host::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        if($host['host_config'] === 'RANDOM')
        {
            $name = substr(md5(microtime()),rand(0,26),15).'.'.$app->getEnv();
            $machine_template['name'] = $name;
            $machine_template['meta']['hostname'] = $name;
        }
        elseif($host['host_config'] === 'FIXED')
        {
            $name = $host['host_name'].'.'.$app->getEnv();
            $machine_template['name'] = $name;
            $machine_template['meta']['hostname'] = $name;
        }
        elseif($host['host_config'] === 'CUSTOM')
        {
            $host = json_decode($app->getRequest()->getParameters()->get('host'));
            foreach ($host as $key => $value)
            {
                $machine_template[$key] = $value;
                if($key === 'name') {
                    $machine_template['name'] = $value.'.'.$app->getEnv();
                    $machine_template['meta']['hostname'] = $value.'.'.$app->getEnv();
                }
            }

        }

        $playbook = new PlayBook();

        $playbook->setName('Create and install a Machine');
        $playbook->setConnection('local');
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $os_server_name = 'Create and install a Machine';
        $os_server_state = 'present';
        $os_server_register = 'newserver';
        $auth = $app->getConfig()->get(OpenStackAuth::class);
        $os_server = $playbook->os_server($os_server_name,$os_server_state,$auth,$machine_template,$os_server_register);

        $tasks = [$os_server];

        $lineinfile_tmp_name = 'Add new Host to tmp file';
        $lineinfile_tmp_path = $machine_access['tmp_file'];
        $lineinfile_tmp_line = '{{ '.$os_server_register.'.server.public_v4 }}';
        $lineinfile_tmp = $playbook->lineinfile($lineinfile_tmp_name,$lineinfile_tmp_path,$lineinfile_tmp_line,'create','yes');

        $lineinfile_inventory_name = 'Add new Host to Inventory';
        $lineinfile_inventory_path = $machine_access['ansible_hosts_file'];
        $lineinfile_inventory_line = '{{ '.$os_server_register.'.server.public_v4 }}';
        $lineinfile_inventory = $playbook->lineinfile($lineinfile_inventory_name,$lineinfile_inventory_path,$lineinfile_inventory_line,'create','yes');

        $post_tasks = [$lineinfile_tmp,$lineinfile_inventory];

        $playbook->tasks($tasks);
        $playbook->post_tasks($post_tasks);

        $unset_parameters = ['pre_tasks','remote_user','connection'];
        $playbook_json = $playbook->toJSON($unset_parameters);

        return $playbook_json;
    }

    private function package(Application $app) {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new PlayBook();

        $playbook->setName('Install Package(s)');
        $playbook->setHosts('{{ lookup(\'file\', \''.$machine_access['tmp_file'].'\') }}');
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

        $raw_name = 'Install Python';
        $raw_install_python_modules = $machine_access['package_manager'].' -y install python-simplejson';
        $raw = $playbook->raw($raw_name,$raw_install_python_modules);

        $package_manager_name = 'Install Packages';
        $package_manager_module = $machine_access['package_manager'];
        $package_manager_items = $app->getRequest()->getParameters()->get('packages');
        $package_manager_state = 'present';
        $package_manager = $playbook->package_manager($package_manager_name,$package_manager_module,$package_manager_items,$package_manager_state);

        $pre_tasks = [$raw];
        $tasks = [$package_manager];

        $playbook->pre_tasks($pre_tasks);
        $playbook->tasks($tasks);

        $unset_parameters = ['post_tasks','become_flags'];
        $playbook_json = $playbook->toJSON($unset_parameters);

        return $playbook_json;
    }


    public function wait(Application $app)
    {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new PlayBook();

        $playbook->setName('Wait');
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $wait_for_name = 'Wait for port 22 to be ready';
        $wait_for_host = '{{ lookup(\'file\', \''.$machine_access['tmp_file'].'\') }}';
        $wait_for_port = '22';
        $wait_for_delay = '10';
        $wait_for = $playbook->wait_for($wait_for_name,$wait_for_host,$wait_for_port,$wait_for_delay);

        $tasks = [$wait_for];

        $playbook->tasks($tasks);

        $unset_parameters = ['pre_tasks','post_tasks','remote_user','connection'];
        $playbook_json = $playbook->toJSON($unset_parameters);

        return $playbook_json;

    }


    public function clean(Application $app) {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new PlayBook();

        $playbook->setName('Clean');
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $file_name = 'Remove tmp file';
        $file_path = $machine_access['tmp_file'];
        $file_state = 'absent';
        $file = $playbook->file($file_name,$file_state,$file_path);

        $tasks = [$file];

        $playbook->tasks($tasks);

        $unset_parameters = ['pre_tasks','post_tasks','remote_user','connection'];
        $playbook_json = $playbook->toJSON($unset_parameters);

        return $playbook_json;
    }
}