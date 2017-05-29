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
use Project\Entity\DB\Host;
use Project\Entity\DB\Jobs;

class AddToHostFile
{
    /**
     * @param $ip
     * @return string
     */
    public function load($app_get)
    {
	$ip = $app_get->get('ip');
        $playbook = new PlayBook();

        $playbook->setName('AddToHostFile');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $lineinfile_inventory = new LineInFile();
        $lineinfile_inventory->setPath('{{ inventory_file }}');
        $lineinfile_inventory->setCreate('yes');
        $lineinfile_inventory->setLine($ip);

        $playbook->setTask($lineinfile_inventory->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
