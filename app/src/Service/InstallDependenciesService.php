<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 15.05.17
 * Time: 15:09
 */

namespace Project\Service;


use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\Raw;
use Project\Entity\JSON\Shell;

class InstallDependenciesService
{

    /**
     * @param $machine_access array
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($machine_access,$app_get)
    {
        $playbook = new PlayBook();

        $playbook->setName('Install Dependencies');
        $playbook->setHosts('{{ lookup(\'file\', \'/tmp/'.$app_get->get('tmp_file').'\') }}');
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

        $raw_rm = new Raw();
        $raw_rm->setRaw('rm /var/lib/apt/lists/*');

        $raw_update = new Raw();
        $raw_update->setRaw('apt-get update');

        $raw_upgrade = new Raw();
        $raw_upgrade->setRaw('apt-get upgrade');

        $raw_simplejson = new Raw();
        $raw_simplejson->setRaw('apt-get -y install python-simplejson');

        $playbook->setTask($raw_rm->toArray());
        $playbook->setTask($raw_update->toArray());
        $playbook->setTask($raw_upgrade->toArray());
        $playbook->setTask($raw_simplejson->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }

}