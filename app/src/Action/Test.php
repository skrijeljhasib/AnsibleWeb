<?php
namespace Project\Action;
use ObjectivePHP\Application\Action\RenderableAction;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\View\Helper\Vars;
use Project\PluginTestService\PluginTester;
/**
 * Class Home
 *
 * @package Showcase\Action
 */
class Test extends RenderableAction
{
    /**
     * @param ApplicationInterface
     *
     * @return array
     */
    public function run(ApplicationInterface $app)
    {
        $tester = new PluginTester();
        $tester->loggerTester();
        $tester->auditTester();
        $tester->mailerTester();
        $tester->filerTester();
        return ['tester'=>$tester];
    }
}
