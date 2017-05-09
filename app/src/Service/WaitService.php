<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 16:00
 */

namespace Project\Service;


use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\WaitFor;

class WaitService
{
    public function load($ansible_api)
    {
        $playbook = new PlayBook();

        $playbook->setName('Wait');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $wait_for = new WaitFor();
        $wait_for->setHost('{{ lookup(\'file\', \''.$ansible_api['tmp_file'].'\') }}');
        $wait_for->setPort('22');
        $wait_for->setDelay('10');

        $playbook->setTask($wait_for->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}