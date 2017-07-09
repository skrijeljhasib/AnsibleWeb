<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 02.06.17
 * Time: 14:16
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\PlayBook;

class AddDnsEntryToOvh
{

    /**
     * @param Application $app
     * @param $ovh_dns_auth
     * @return string
     */
    public function load(Application $app, $ovh_dns_auth)
    {
        $playbook = new PlayBook();

	$playbook->init('Add Dns Entry to Ovh', 'local', 'false', 'www-data',
                                        '-s /bin/sh', 'localhost', 'false');
        $playbook->setEnvironment($ovh_dns_auth);

	$playbook->setTask([ "ovh_dns" => [
          "name" => $app->getRequest()->getParameters()->get('host_name'),
          "type" => $app->getRequest()->getParameters()->get('type'),
          "state" => "present",
	  "domain" => $app->getRequest()->getParameters()->get('domain_name'),
	  "value" => $app->getRequest()->getParameters()->get('ip')
        ]]);

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }

}
