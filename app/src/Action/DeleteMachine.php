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

class DeleteMachine extends RenderableAction
{

    /**
     * @param ApplicationInterface $app
     * @return array
     * @throws Exception
     */
    function run(ApplicationInterface $app)
    {
        Vars::set('page.title', 'Delete a machine');

        $host = $app->getConfig()->get(Host::class);

        return compact('host', 'ansible_api');
    }
}
