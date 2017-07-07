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
use Project\Entity\JSON\Apt;
use Project\Entity\JSON\File;
use Project\Entity\JSON\LineInFile;
use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\Service;
use Project\Entity\JSON\Shell;

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

        $apt = new Apt();
        $apt->setState(Apt::PRESENT);
        $apt->setAName('apache2');

        $file = new File();
        $file->setState('hard');
        $file->setSrc('/etc/apache2/sites-available/000-default.conf');
        $file->setPath('/etc/apache2/sites-available/{{ansible_hostname}}.conf');

        $lineinfile = new LineInFile();
        $lineinfile->setPath('/etc/apache2/sites-available/{{ansible_hostname}}.conf');
        $lineinfile->setBackrefs('yes');
        $lineinfile->setRegexp('DocumentRoot');
        $lineinfile->setLine('DocumentRoot '.$app_get->get('document_root'));

        $shell = new Shell();
        $shell->setShell('a2ensite {{ansible_hostname}} ; a2dissite 000-default');

        $file = new File();
	$file->setPath($app_get->get('document_root'));
        $file->setState('directory');

        $service = new Service();
        $service->setSName('apache2');
        $service->setState('restarted');

        $this->playbook->setTask($apt->toArray());
        $this->playbook->setTask($file->toArray());
        $this->playbook->setTask($lineinfile->toArray());
        $this->playbook->setTask($shell->toArray());
        $this->playbook->setTask($service->toArray());

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }

    /**
     * @return string
     */
    public function nginx()
    {
        $this->playbook->setName('Install NginX');

        $apt = new Apt();
        $apt->setState(Apt::PRESENT);
        $apt->setAName('nginx');

        $this->playbook->setTask($apt->toArray());

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}
