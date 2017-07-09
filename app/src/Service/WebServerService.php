<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 10:32
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;
use Project\Entity\JSON\PlayBook;

class WebServerService
{

    /**
     * @var PlayBook
     */
    private $playbook;

    /**
     * @param $machine_access array
     * @param $app Application
     */
    public function load($machine_access, $app)
    {

        $this->playbook = new PlayBook();

        $this->playbook->setHosts($app->getRequest()->getParameters()->get('ip'));
        $this->playbook->setConnection('ssh');
        $this->playbook->setRemoteUser($machine_access['remote_user']);
        $this->playbook->setBecome('true');
        $this->playbook->setBecomeMethod('sudo');
        $this->playbook->setBecomeUser('root');
        $this->playbook->setGatherFacts('true');
    }

    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function apache($app_get)
    {
        $this->playbook->setName('Install and Configure Apache');

        $this->playbook->setTask([ "apt" => [  "name" => "apache2", "state" => "present" ] ]);
        
	$this->playbook->setTask([ "file" => [  "src" => "/etc/apache2/sites-available/000-default.conf", 
						"path" => "/etc/apache2/sites-available/{{ansible_hostname}}.conf",
						"state" => "hard" ] ]);

        $this->playbook->setTask([ "lineinfile" => [  	"path" => "/etc/apache2/sites-available/{{ansible_hostname}}.conf",
							"backrefs" => "yes",
							"regexp" => "DocumentRoot",
                                                	"line" => "DocumentRoot " . $app_get->get('document_root') ] ]);

	$this->playbook->setTask([ "shell" => "a2ensite {{ansible_hostname}} ; a2dissite 000-default" ]);

	$this->playbook->setTask([ "apache2_module" => [  "name" => "rewrite", "state" => "present" ] ]);

	$this->playbook->setTask([ "service" => [  "name" => "apache2", "state" => "restarted" ] ]);

	$this->playbook->setTask([ "file" => [ 	"path" => $app_get->get('document_root'), 
						"owner" => $app_get->get('owner_directory'),
						"state" => "directory" ] ]);

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }

    /**
     * @return string
     */
    public function nginx()
    {
        $this->playbook->setName('Install NginX');

	$this->playbook->setTask([ "apt" => [  "name" => "nginx", "state" => "present" ] ]);

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}
