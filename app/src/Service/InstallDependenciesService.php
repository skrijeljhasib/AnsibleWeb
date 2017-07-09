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

class InstallDependenciesService
{

    /**
     * @param $machine_access array
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($machine_access, $app_get)
    {
	$step = $app_get->get('step');
        $playbook = new PlayBook();

        $playbook->setName('Install dependencies ' . $step);
        $playbook->setHosts($app_get->get('ip'));
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

	if ($step == 1) { 
		$playbook->setTask([ "raw" => "apt-get update" ]);
	}

        if ($step == 2) {
                $playbook->setTask([ "raw" => "apt-get upgrade -y" ]);
        }

        if ($step == 3) {
                $playbook->setTask([ "raw" => "apt-get update; apt-get upgrade -y; apt-get install -y python-apt aptitude" ]);
        }

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
