<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 16:00
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;

class WaitSSHService
{
    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($app_get,$url)
    {
        $contents = file_get_contents($url . '/repo/waitforssh/waitforssh.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
        return $contents;
    }
}
