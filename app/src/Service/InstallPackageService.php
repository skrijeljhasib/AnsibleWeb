<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:55
 */

namespace Project\Service;


use Project\Entity\JSON\Apt;
use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\Raw;

class InstallPackageService
{
    public function load($machine_access, $app_get)
    {
        $playbook = new PlayBook();

        $playbook->setName('Install Package(s)');
        $playbook->setHosts('{{ lookup(\'file\', \'/tmp/'.$app_get->get('tmp_file').'\') }}');
        $playbook->setConnection('ssh');
        $playbook->setRemoteUser($machine_access['remote_user']);
        $playbook->setBecome('true');
        $playbook->setBecomeMethod('sudo');
        $playbook->setBecomeUser('root');
        $playbook->setGatherFacts('false');

        $raw = new Raw();
        $raw->setRaw('apt -y install aptitude python-apt');

        $apt_update = new Apt();
        $apt_update->setUpdateCache('yes');

        $apt_upgrade = new Apt();
        $apt_upgrade->setUpgrade('full');

        $apt = new Apt();
        $apt->setAName(Apt::MULTIPLE_ITEMS);
        $apt->setState(Apt::LATEST);
        $apt->setWithItems($app_get->get('packages'));

        $playbook->setPreTask($raw->toArray());
        $playbook->setTask($apt_update->toArray());
        $playbook->setTask($apt_upgrade->toArray());
        $playbook->setTask($apt->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}