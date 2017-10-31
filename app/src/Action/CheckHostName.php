<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 26.05.17
 * Time: 17:15
 */

namespace Project\Action;


use ObjectivePHP\Html\Exception;
use Project\Config\DnsConfig;
use Project\Application;

class CheckHostName
{

    public function __invoke(Application $app)
    {
	$dns_config = $app->getConfig()->get(DnsConfig::class);
	if (!preg_match('/'. $dns_config['env'] .'/' , $app->getRequest()->getParameters()->get('name'))) {
		die('nok');
	}
        try {
            $host_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $hosts = $host_gateway->fetch();
        } catch (Exception $e) {
            throw new Exception('Can not load hosts from DB');
        }

        $name = [];

        foreach ($hosts as $host) {
            $name[] = $host->getName();
        }

        if(!in_array( $app->getRequest()->getParameters()->get('name'),$name)) {
            echo 'ok';
        }
    }

}
