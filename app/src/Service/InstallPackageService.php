<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:55
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\JSON\Apt;
use Project\Entity\JSON\PlayBook;

class InstallPackageService
{
    /**
     * @param $machine_access array
     * @param $app Application
     * @return string
     */
    public function load($machine_access, $app)
    {
        $playbook = new PlayBook();

        $playbook->setName('Install package(s)');
        $playbook->setHosts($app->getRequest()->getParameters()->get('ip'));
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

        $apt = new Apt();
        $apt->setAName(Apt::MULTIPLE_ITEMS);
        $apt->setState(Apt::PRESENT);
        $apt->setWithItems(explode(',', $app->getRequest()->getParameters()->get('packages')));

        $playbook->setTask($apt->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}
