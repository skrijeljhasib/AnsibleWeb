<?php

namespace Project\Action;

use Project\Application;
use Project\Config\Host;
use Project\Config\MachineTemplate;
use Project\Config\MachineAccess;
use Project\Config\OpenStackAuth;
use Project\Entity\JSON\Apt;
use Project\Entity\JSON\File;
use Project\Entity\JSON\LineInFile;
use Project\Entity\JSON\OsServer;
use Project\Entity\JSON\OsServerAuth;
use Project\Entity\JSON\Raw;
use Project\Entity\JSON\WaitFor;
use stdClass;

class PlayBook
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
        $openstack_auth = $app->getConfig()->get(OpenStackAuth::class);
        $host = $app->getConfig()->get(Host::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        if($host['host_config'] === 'RANDOM')
        {
            $name = substr(md5(microtime()),rand(0,26),15).'.'.$app->getEnv();
            $machine_template['name'] = $name;
        }
        elseif($host['host_config'] === 'FIXED')
        {
            $name = $host['host_name'].'.'.$app->getEnv();
            $machine_template['name'] = $name;
        }
        elseif($host['host_config'] === 'CUSTOM')
        {
            $host = json_decode($app->getRequest()->getParameters()->get('host'));
            foreach ($host as $key => $value)
            {
                $machine_template[$key] = $value;
                if($key === 'name') {
                    $machine_template['name'] = $value.'.'.$app->getEnv();
                }
            }
        }

        $playbook = new \Project\Entity\JSON\PlayBook();

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
        $lineinfile_tmp->setPath($machine_access['tmp_file']);
        $lineinfile_tmp->setCreate('yes');
        $lineinfile_tmp->setLine('{{ '.$os_server->getRegister().'.server.public_v4 }}');

        $lineinfile_inventory = new LineInFile();
        $lineinfile_inventory->setPath($machine_access['ansible_hosts_file']);
        $lineinfile_inventory->setCreate('yes');
        $lineinfile_inventory->setLine('{{ '.$os_server->getRegister().'.server.public_v4 }}');

        $playbook->setTask($os_server->toArray());
        $playbook->setPostTask($lineinfile_tmp->toArray());
        $playbook->setPostTask($lineinfile_inventory->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }

    private function package(Application $app) {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new \Project\Entity\JSON\PlayBook();

        $playbook->setName('Install Package(s)');
        $playbook->setHosts('{{ lookup(\'file\', \''.$machine_access['tmp_file'].'\') }}');
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

        $raw = new Raw();
        $raw->setRaw('apt -y install aptitude python-apt');

        $apt_update = new Apt();
        $apt_update->setUpdateCache('yes');

        $apt_upgrade = new Apt();
        $apt_upgrade->setUpgrade('full');

        $apt = new Apt();
        $apt->setAName(Apt::MULTIPLE_ITEMS);
        $apt->setState(Apt::LATEST);
        $apt->setWithItems($app->getRequest()->getParameters()->get('packages'));

        $playbook->setPreTask($raw->toArray());
        $playbook->setTask($apt_update->toArray());
        $playbook->setTask($apt_upgrade->toArray());
        $playbook->setTask($apt->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }


    public function wait(Application $app)
    {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new \Project\Entity\JSON\PlayBook();

        $playbook->setName('Wait');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $wait_for = new WaitFor();
        $wait_for->setHost('{{ lookup(\'file\', \''.$machine_access['tmp_file'].'\') }}');
        $wait_for->setPort('22');
        $wait_for->setDelay('10');

        $playbook->setTask($wait_for->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;

    }


    public function clean(Application $app) {
        $machine_access = $app->getConfig()->get(MachineAccess::class);

        $playbook = new \Project\Entity\JSON\PlayBook();

        $playbook->setName('Clean');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $file = new File();
        $file->setState('absent');
        $file->setPath($machine_access['tmp_file']);;

        $playbook->setTask($file->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}