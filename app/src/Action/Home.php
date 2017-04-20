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
     * @internal param $ApplicationInterface
     *
     */
    public function run(ApplicationInterface $app)
    {
        // Vars class holds view variables
        //
        // you can return an array of view variables or
        // directly call it

        Vars::set('page.title', 'Welcome to AnsibleWeb');
    }
}