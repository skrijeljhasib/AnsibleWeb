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

class InstallServer extends RenderableAction
{

    function run(ApplicationInterface $app)
    {
        Vars::set('page.title', 'Install a server');
    }

}