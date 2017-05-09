<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 09.05.17
 * Time: 16:02
 */

namespace Project\Service;


use Project\Entity\JSON\File;
use Project\Entity\JSON\PlayBook;

class CleanService
{
    public function load($ansible_api)
    {
        $playbook = new PlayBook();

        $playbook->setName('Clean');
        $playbook->setBecome('false');
        $playbook->setBecomeUser('www-data');
        $playbook->setBecomeFlags('-s /bin/sh');
        $playbook->setHosts('localhost');
        $playbook->setGatherFacts('false');

        $file = new File();
        $file->setState('absent');
        $file->setPath($ansible_api['tmp_file']);;

        $playbook->setTask($file->toArray());

        $playbook_json = $playbook->toJSON();

        return $playbook_json;
    }
}