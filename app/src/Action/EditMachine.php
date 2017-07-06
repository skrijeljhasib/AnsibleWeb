<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 01.06.17
 * Time: 16:57
 */

namespace Project\Action;

use Project\Entity\DB\Host;
use ObjectivePHP\Html\Exception;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Action\AjaxAction;

class EditMachine extends AjaxAction
{

    public function run(ApplicationInterface $app)
    {
        try {
            $host_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $host = $host_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
	    if (!$host) { 
		$host = new Host();
		$host->setName($app->getRequest()->getParameters()->get('name'));
	    }
	    if ($app->getRequest()->getParameters()->get('group')) {
	    	$host->setHostGroup($app->getRequest()->getParameters()->get('group'));
	    }
	    if ($app->getRequest()->getParameters()->get('ip')) { 
		$host->setIp($app->getRequest()->getParameters()->get('ip'));
	    }
            if ($app->getRequest()->getParameters()->get('hostlocation')) {
                $host->setLocation($app->getRequest()->getParameters()->get('hostlocation'));
            }
	    if ($app->getRequest()->getParameters()->get('hoststatus')) {
                $host->setStatus($app->getRequest()->getParameters()->get('hoststatus'));
                $host->setHostId($app->getRequest()->getParameters()->get('name'));
                $host->setState(0);
            }
            $host_gateway->put($host);
        } catch (Exception $e) {
            throw new Exception('Can not load hosts from DB');
        }

        return $host;
    }

}
