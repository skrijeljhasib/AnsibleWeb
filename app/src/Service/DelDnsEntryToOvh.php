<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 02.06.17
 * Time: 14:16
 */

namespace Project\Service;

use Project\Application;

class DelDnsEntryToOvh
{

    /**
     * @param Application $app
     * @param $ovh_dns_auth
     * @return string
     */
    public function load(Application $app, $ovh_dns_auth, $url)
    {
	$contents = file_get_contents($url . '/repo/dns_delete/delete.json');
        $contents = str_replace("{{{ DNS_URL }}}",$ovh_dns_auth['OVH_ENDPOINT'],$contents);
        $contents = str_replace("{{{ DNS_A_KEY }}}",$ovh_dns_auth['OVH_APPLICATION_KEY'],$contents);
        $contents = str_replace("{{{ DNS_SECRET }}}",$ovh_dns_auth['OVH_APPLICATION_SECRET'],$contents);
        $contents = str_replace("{{{ DNS_C_KEY }}}",$ovh_dns_auth['OVH_CONSUMER_KEY'],$contents);
        $contents = str_replace("{{{ HOST_IP }}}",$app->getRequest()->getParameters()->get('ip'),$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$app->getRequest()->getParameters()->get('host_name'),$contents);
        $contents = str_replace("{{{ DNS_TYPE }}}",$app->getRequest()->getParameters()->get('type'),$contents);
        $contents = str_replace("{{{ HOST_DOMAIN }}}",$app->getRequest()->getParameters()->get('domain_name'),$contents);
        return $contents;
    }

}
