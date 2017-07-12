<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 15:55
 */

namespace Project\Service;

use Project\Application;

class InstallPackageService
{
    /**
     * @param $machine_access array
     * @param $app Application
     * @return string
     */
    public function load($machine_access, $app, $url)
    {
        $contents = file_get_contents($url . '/repo/install_pkg/install.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app->get('ip'),$contents);
        $contents = str_replace("{{{ HOST_PKG }}}",explode(',', $app->get('packages')),$contents);
        return $contents;
    }
}
