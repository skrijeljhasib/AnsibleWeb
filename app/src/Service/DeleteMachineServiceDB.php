<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\DB\Job;

class DeleteMachineServiceDB
{
    /**
     * @param $app Application
     */
    public function load($app)
    {
        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = $hosts_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
        $hosts_gateway->delete($host);

    }
}
