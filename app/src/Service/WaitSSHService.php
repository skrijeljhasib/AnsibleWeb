<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 16:00
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Entity\JSON\PlayBook;

class WaitSSHService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get)
    {
        $playbook = new PlayBook();

        $playbook->setName('Wait for SSH Connection');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

	$playbook->setTask([ "wait_for" => [ "host" => $app_get->get('ip'), "port" => "22" ] ]);

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
