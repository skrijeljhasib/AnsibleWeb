<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\Host;
use Project\Entity\Order;

class DeleteMachineServiceDB
{
    /**
     * @param $app Application
     */
    public function load($app)
    {
        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
	$orders_gateway = $app->getServicesFactory()->get('gateway.orders');
        $host = $hosts_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
        $order = $orders_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
        if ($host) { $hosts_gateway->delete($host); }
	if ($order) { $orders_gateway->delete($order); }

    }
}
