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

class InstallDependenciesService
{

    /**
     * @param $machine_access array
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($machine_access, $app_get)
    {
        $ip = $app_get->get('ip');
	$step = $app_get->get('step');
        $playbook = new PlayBook();

        $playbook->setName('Install dependencies ' . $step);
        $playbook->setHosts($ip);
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

	if ($step == 1) { 
        	$raw = new Raw();
        	$raw->setRaw('apt-get update');
	}

        if ($step == 2) {
                $raw = new Raw();
                $raw->setRaw('apt-get upgrade -y');
        }

        if ($step == 3) {
                $raw = new Raw();
                $raw->setRaw('apt-get install -y python-apt aptitude');
        }

        $playbook->setTask($raw->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
