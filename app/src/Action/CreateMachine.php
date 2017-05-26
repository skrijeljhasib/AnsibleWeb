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
use Project\Config\Host;
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
        Vars::set('page.title', 'Create your machine');

        $host = $app->getConfig()->get(Host::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);

        try {
            $package_gateway = $app->getServicesFactory()->get('gateway.packages');
            $packages = $package_gateway->fetch();
        } catch (Exception $e) {
            throw new Exception('Can not load packages from DB');
        }

        return compact('packages', 'host', 'ansible_api', 'machine_template');
    }
}
