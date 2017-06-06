<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;
use Project\Entity\JSON\LineInFile;
use Project\Entity\JSON\PlayBook;

class AddToHostFile
{
    /**
     * @param $app Application
     * @return string
     */
    public function load()
    {
        //$playbook = new PlayBook();

/*        $playbook->setName('AddAllToHostFile');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $lineinfile_inventory = new LineInFile();
        $lineinfile_inventory->setPath('{{ inventory_file }}');
        $lineinfile_inventory->setCreate('yes');
        $lineinfile_inventory->setLine($app->getRequest()->getParameters()->get('ip'));

        $playbook->setTask($lineinfile_inventory->toArray());*/

        //$playbook_json = $playbook->toJSON();
	$playbook_json = '
		{  
   "name":"AddAllToHostFile",
   "hosts":"localhost",
   "gather_facts":"false",
   "connection":"local",
   "become":"false",
   "become_user":"www-data",
   "become_flags":"-s /bin/sh",
   "tasks":[  
      {  
         "copy":{ 
		"src": "/tmp/ansible_host"
    		"dest":"{{ inventory_file }}"
         }
      }
   ]
}
	';

        return $playbook_json;
    }
}
