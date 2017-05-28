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
use Project\Entity\JSON\WaitFor;

class WaitSSHService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get)
    {
	$ip = $app_get->get('ip');
        $playbook = new PlayBook();

        $playbook->setName('Wait for SSH');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $wait_for = new WaitFor();
        $wait_for->setHost($ip);
        $wait_for->setPort('22');

        $playbook->setTask($wait_for->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
