<?php

namespace Project\Service;

use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\Shell;

class NotifyService
{
    /**
     * @return string
     */
    public function load()
    {
        $playbook = new PlayBook();

        $playbook->setName('Install Machine Notification');
        $playbook->setConnection('local');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $shell = new Shell();
        $shell->setShell("echo 'Machine Installed'");

        $playbook->setTask($shell->toArray());

        $playbook_json = $playbook->toJSON();
        return $playbook_json;
    }
}
