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

class DelDnsEntryToOvh
{

    /**
     * @param Application $app
     * @param $ovh_dns_auth
     * @return string
     */
    public function load(Application $app, $ovh_dns_auth, $url)
    {
        $playbook = new PlayBook();

	$playbook->init('Del Dns Entry to Ovh', 'local', 'false', 'www-data',
                                        '-s /bin/sh', 'localhost', 'false');
        $playbook->setEnvironment($ovh_dns_auth);

        $playbook->setTask([ "ovh_dns" => [
          "name" => $app->getRequest()->getParameters()->get('host_name'),
          "type" => $app->getRequest()->getParameters()->get('type'),
          "state" => "absent",
          "domain" => $app->getRequest()->getParameters()->get('domain_name'),
          "value" => $app->getRequest()->getParameters()->get('ip')
        ]]);

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }

}
