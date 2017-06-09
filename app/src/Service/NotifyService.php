<?php

namespace Project\Service;

use Project\Entity\JSON\PlayBook;

class NotifyService
{
    /**
     * @return string
     */
    public function load($app_get)
      {
        $ip = $app_get->get('ip');
        $playbook = new PlayBook();
	$playbook->init('Install Machine Notification', 'local', 'false', 'www-data',
                                         '-s /bin/sh', 'localhost', 'false');
         $playbook->setTask(["shell" => "echo 'Machine " . $ip . " Installed' | mail -s test l.venier@flash-global.net"]);
         $playbook_json = $playbook->toJSON();

          return $playbook_json;
      }
}
