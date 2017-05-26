<?php

namespace Project\Action;

use ObjectivePHP\Application\Action\RenderableAction;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\View\Helper\Vars;

/**
 * Class Home
 *
 * @package Showcase\Action
 */
class Home extends RenderableAction
{
    /**
     * @param ApplicationInterface $app
     * @return array
     */
    public function run(ApplicationInterface $app)
    {
        Vars::set('page.title', 'Welcome to AnsibleWeb');
    }
}
