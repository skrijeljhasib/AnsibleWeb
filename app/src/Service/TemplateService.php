<?php

namespace Project\Service;

use Project\Entity\JSON\PlayBook;
use Project\Entity\JSON\Shell;

class NotifyService
{
    /**
     * @return string
     */
    public function load()
    {
        
        return file_get_contents('http://stackstorm.test.flash-global.net:8888/repo/template.json');
    }
}
