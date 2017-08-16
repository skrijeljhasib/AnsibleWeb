<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:22
 */

namespace Project\Service;

use Project\Application;

class DeleteMachineService
{
    /**
     * @param $openstack_auth array
     * @param $app application
     * @param $app url
     * @return string
     */
    public function load($openstack_auth, $app, $url)
    {
        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        $host = $hosts_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));

        $contents = file_get_contents($url . '/repo/machine_delete/delete.json');
        $contents = str_replace("{{{ AUTH_URL }}}",$openstack_auth['auth_url'],$contents);
        $contents = str_replace("{{{ AUTH_USERNAME }}}",$openstack_auth['username'],$contents);
        $contents = str_replace("{{{ AUTH_PASSWORD }}}",$openstack_auth['password'],$contents);
        $contents = str_replace("{{{ AUTH_PROJECT }}}",$openstack_auth['project_name'],$contents);
        $contents = str_replace("{{{ HOST_REGION }}}",$host->getLocation(),$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$host->getName(),$contents);

        $host->setStatus('DELETING');
        $hosts_gateway->put($host);

        $orders_gateway = $app->getServicesFactory()->get('gateway.orders');
        $order = $orders_gateway->fetchByName($host->getName());
        if ($order) { $orders_gateway->delete($order); }

        return $contents;
    }

    public function dns($openstack_auth, $app, $url)
    {
        $contents = file_get_contents($url . '/repo/deploy/machine_delete/delete.json');
        $contents = str_replace("{{{ AUTH_URL }}}",$openstack_auth['auth_url'],$contents);
        $contents = str_replace("{{{ AUTH_USERNAME }}}",$openstack_auth['username'],$contents);
        $contents = str_replace("{{{ AUTH_PASSWORD }}}",$openstack_auth['password'],$contents);
        $contents = str_replace("{{{ AUTH_PROJECT }}}",$openstack_auth['project_name'],$contents);
        $contents = str_replace("{{{ HOST_REGION }}}",$host->getLocation(),$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$host->getName(),$contents);

        return $contents;
    }
}
