<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 15.05.17
 * Time: 15:09
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;

class InstallDependenciesService
{

    /**
     * @param $machine_access array
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function load($machine_access, $app_get, $url)
    {
        $contents = file_get_contents($url . '/repo/install_dependencies/' . $app_get->get('step') . '.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
        return $contents;
    }
}
