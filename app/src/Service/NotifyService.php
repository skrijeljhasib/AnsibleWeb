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

	$playbook->init('Install Machine Notification', 'local', 'false', 'www-data',
                                        '-s /bin/sh', 'localhost', 'false');

        $shell = new Shell();
        $shell->setShell("echo 'Machine Installed'");

        $playbook->setTask($shell->toArray());

        $playbook_json = $playbook->toJSON();
        return $playbook_json;
    }
}
