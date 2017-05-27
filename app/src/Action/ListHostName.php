<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 26.05.17
 * Time: 17:15
 */

namespace Project\Action;


use ObjectivePHP\Html\Exception;
use Project\Application;

class ListHostName
{

    public function __invoke(Application $app)
    {

        try {
            $host_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $hosts = $host_gateway->fetch();
        } catch (Exception $e) {
            throw new Exception('Can not load hosts from DB');
        }
	foreach ($hosts as $host) {
	   if ($app->getRequest()->getParameters()->get('nametodelete') == $host->getName()) {
		$hostname[] = $host->getName();
	    }
        }

	echo json_encode($hostname);
    }

}
