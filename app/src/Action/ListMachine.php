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
use Project\Config\AnsibleApi;
use Project\Config\Host;

class CreateMachine extends RenderableAction
{

    /**
     * @param ApplicationInterface $app
     * @return array
     * @throws Exception
     */
    function run(ApplicationInterface $app)
    {
        Vars::set('page.title', 'Machine List');

        try
        {
            $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $hosts = $hosts_gateway->fetch();
        }
        catch (Exception $e)
        {
            throw new Exception('Can not load hosts from DB');
        }

        return compact('hosts', 'ansible_api');
    }

}
