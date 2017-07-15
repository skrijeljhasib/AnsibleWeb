<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 10:32
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;

class WebServerService
{

    /**
     * @param $machine_access array
     * @param $app Application
     */
    public function loadapache($app, $url)
    {
        $contents = file_get_contents($url . '/repo/apache2_install/install.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app->get('ip'),$contents);
	if (!empty($app->get('document_root'))) {
        	$contents = str_replace("{{{ DOC_ROOT }}}",$app->get('document_root'),$contents);
		$contents = str_replace("{{{ DOC_ROOT_FLAG }}}","true",$contents);
		if (!empty($app->get('owner_directory'))) {
			$contents = str_replace("{{{ DOC_OWNER }}}",$app->get('owner_directory'),$contents);
			$contents = str_replace("{{{ DOC_OWNER_FLAG }}}","true",$contents);
		}
        }
	return $contents;
    }

    /**
     * @return string
     */
    public function loadnginx($app, $url)
    {

        return false;
    }
}
