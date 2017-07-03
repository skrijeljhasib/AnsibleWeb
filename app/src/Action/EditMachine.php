<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 01.06.17
 * Time: 16:57
 */

namespace Project\Action;

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
	    $host->setHostGroup($app->getRequest()->getParameters()->get('group'));
            $host_gateway->put($host);
        } catch (Exception $e) {
            throw new Exception('Can not load hosts from DB');
        }

        return $host;
    }

}
