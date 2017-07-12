<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 07.06.17
 * Time: 17:05
 */

namespace Project\Service;

use Project\Application;
use Project\Entity\PlayBook;

class LanguageService
{
    /**
     * @var PlayBook
     */
    private $playbook;

    /**
     * @param $machine_access array
     * @param $app Application
     */
    public function load($app, $url)
    {
        $contents = file_get_contents($url . '/repo/language_install/php.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app->get('ip'),$contents);
        return $contents;
    }
}
