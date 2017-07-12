<?php

namespace Project\Service;

use Project\Entity\PlayBook;

class NotifyService
{
    /**
     * @return string
     */
    public function load($app_get,$url)
      {
        $contents = file_get_contents($url . '/repo/notify_install/install.json');
        $contents = str_replace("{{{ HOST_IP }}}",$app_get->get('ip'),$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$app_get->get('name'),$contents);
        return $contents;
      }
}
