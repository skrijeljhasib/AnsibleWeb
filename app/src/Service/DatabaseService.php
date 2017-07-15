<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 17:09
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;

class DatabaseService
{
    /**
     * @param $machine_access array
     * @param $app Application
     */
    public function loadmysql($app, $url)
    {
        $contents = file_get_contents($url . '/repo/mysql_install/install.json');
        $contents = str_replace("{{{ MYSQL_ROOT_PWD }}}",$app->get('mysql_root_password'),$contents);
        $contents = str_replace("{{{ HOST_IP }}}",$app->get('ip'),$contents);

	if (!empty($app->get('mysql_database'))) {
		$contents = str_replace("{{{ MYSQL_DB_FLAG }}}","true",$contents);
        	$contents = str_replace("{{{ MYSQL_DB }}}",$app->get('mysql_database'),$contents);
	} else {
		$contents = str_replace("{{{ MYSQL_DB_FLAG }}}","false",$contents);
	}

        if ((!empty($app->get('mysql_new_user'))) && (!empty($app->get('mysql_new_user_password')))) {
                $contents = str_replace("{{{ MYSQL_USER_FLAG }}}","true",$contents);
                $contents = str_replace("{{{ MYSQL_USER }}}",$app->get('mysql_new_user'),$contents);
                $contents = str_replace("{{{ MYSQL_U_PWD }}}",$app->get('mysql_new_user_password'),$contents);
        } else {
                $contents = str_replace("{{{ MYSQL_USER_FLAG }}}","false",$contents);
        }

        return $contents;
    }

    /**
     * @return string
     */
    public function mongodb()
    {
        return false;
    }
}
