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

class DeployMachine extends RenderableAction
{

    /**
     * @param ApplicationInterface $app
     * @return array
     * @throws Exception
     */
    function run(ApplicationInterface $app)
    {
	$user = $app->getServicesFactory()->get('connect.client')->getUser()->toArray();
        Vars::set('page.title', 'Deploy Application');

        $host = $app->getConfig()->get(Host::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);
        $dns_config = $app->getConfig()->get(DnsConfig::class);
        $default_install = $app->getConfig()->get(DefaultInstall::class);

        return compact('host', 'machine_template', 'dns_config', 'default_install');
    }
}
