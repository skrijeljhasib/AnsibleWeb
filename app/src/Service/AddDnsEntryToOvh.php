<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 02.06.17
 * Time: 14:16
 */

namespace Project\Service;


use Project\Application;
use Project\Entity\JSON\OvhDns;
use Project\Entity\JSON\PlayBook;

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

        $ovhdns = new OvhDns();
        $ovhdns->setHName($app->getRequest()->getParameters()->get('host_name'));
        $ovhdns->setState('present');
        $ovhdns->setType($app->getRequest()->getParameters()->get('type'));
        $ovhdns->setDomain($app->getRequest()->getParameters()->get('domain_name'));
        $ovhdns->setValue($app->getRequest()->getParameters()->get('ip'));

        $playbook->setTask($ovhdns->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }

}
