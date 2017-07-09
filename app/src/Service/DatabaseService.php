<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 17:09
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;
use Project\Entity\PlayBook;

class DatabaseService
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
        $this->playbook->setGatherFacts('false');
    }


    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function mysql($app_get)
    {

        $this->playbook->setName('Install and Configure MySQL');

        $this->playbook->setTask([ 	"apt" => [  "name" => "{{ item }}", "state" => "present" ],
					"with_items" => [ "mysql-server", "mysql-client", "python-mysqldb" ] ]);

        $this->playbook->setTask([ "mysql_user" => [
          "name" => "root",
          "password" => $app_get->get('mysql_root_password'),
          "update_password" => "always"
        ]]);

	$this->playbook->setTask([ "mysql_db" => [
	  "name" => $app_get->get('mysql_database'),
	  "state" => "present",
	  "login_user" => "root",
          "login_password" => $app_get->get('mysql_root_password')
	]]);

        $this->playbook->setTask([ "mysql_user" => [
          "name" => $app_get->get('mysql_new_user'),
          "password" => $app_get->get('mysql_new_user_password'),
	  "priv" => $app_get->get('mysql_database').".*:ALL",
          "login_user" => "root",
          "login_password" => $app_get->get('mysql_root_password'),
          "state" => "present"
        ]]);

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }

    /**
     * @return string
     */
    public function mongodb()
    {
        $this->playbook->setName('Install MongoDB');

        $this->playbook->setTask([      "apt" => [  "pkg" => "{{ item }}", "state" => "present" ],
                                        "with_items" => [ "mongodb", "python-mongodb" ] ]);

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}
