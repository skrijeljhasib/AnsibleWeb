<?php

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Entity\JSON\PlayBook;

class NotifyService
{
    /**
     * @return string
     */
    public function load()
    {
	    $playbook_json = file_get_contents('http://stackstorm.test.flash-global.net:8888/repo/notification.json');
        return $playbook_json;
    }
}
