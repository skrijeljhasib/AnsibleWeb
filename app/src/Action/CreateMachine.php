<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 12.04.17
 * Time: 11:15
 */

namespace Project\Action;

use ObjectivePHP\Application\Action\RenderableAction;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\View\Helper\Vars;
use ObjectivePHP\Html\Exception;
use Project\Config\Monitoring;
use Project\Config\DnsConfig;
use Project\Config\Host;
use Project\Config\DefaultInstall;
use Project\Config\MachineTemplate;

class CreateMachine extends RenderableAction
{

    /**
     * @param ApplicationInterface $app
     * @return array
     * @throws Exception
     */
    function run(ApplicationInterface $app)
    {
	$user = $app->getServicesFactory()->get('connect.client')->getUser()->toArray();
	if (($user["current_role"]) != 'ADMIN') {
		throw new Exception('Not Allowed');
	 }
        Vars::set('page.title', 'Create your machine');

        $host = $app->getConfig()->get(Host::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);
        $monitoring = $app->getConfig()->get(Monitoring::class);
        $dns_config = $app->getConfig()->get(DnsConfig::class);
        $default_install = $app->getConfig()->get(DefaultInstall::class);

        try {
            $package_gateway = $app->getServicesFactory()->get('gateway.packages');
            $packages = $package_gateway->fetch();
        } catch (Exception $e) {
            throw new Exception('Can not load packages from DB');
        }

        return compact('packages', 'host', 'machine_template', 'monitoring', 'dns_config', 'default_install');
    }
}
