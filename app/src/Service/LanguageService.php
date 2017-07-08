<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 07.06.17
 * Time: 17:05
 */

namespace Project\Service;


use Project\Application;
use Project\Entity\JSON\Apt;
use Project\Entity\JSON\PlayBook;

class LanguageService
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
     * @param $app Application
     * @return string
     */
    public function php($app) {

        $this->playbook->setName('Install '.$app->getRequest()->getParameters()->get('language'));
/*        $apt = new Apt();
        $apt->setAName($app->getRequest()->getParameters()->get('php_version'));
        $apt->setState(Apt::PRESENT);
        $this->playbook->setTask($apt->toArray());*/

	$this->playbook->setTask([ "apt" => [  "name" => $app->getRequest()->getParameters()->get('php_version'),
                                               "state" => "present" ] ]);
	$this->playbook->setTask([ "apt" => [  "name" => "php-curl",
                                               "state" => "present" ] ]);
	$this->playbook->setTask([ "apt" => [  "name" => "php-mysql",
                                               "state" => "present" ] ]);

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}
