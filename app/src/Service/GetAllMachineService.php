<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 10.05.17
 * Time: 15:34
 */
namespace Project\Service;
class GetAllMachineService
{
    /**
     * @param $machine_template array
     * @param $openstack_auth array
     * @return string
     */
    public function load($machine_template, $openstack_auth,$url)
    {
        $contents = file_get_contents($url . '/repo/machine_getall/getall.json');
        $contents = str_replace("{{{ AUTH_URL }}}",$openstack_auth['auth_url'],$contents);
        $contents = str_replace("{{{ AUTH_USERNAME }}}",$openstack_auth['username'],$contents);
        $contents = str_replace("{{{ AUTH_PASSWORD }}}",$openstack_auth['password'],$contents);
        $contents = str_replace("{{{ AUTH_PROJECT }}}",$openstack_auth['project_name'],$contents);
        $contents = str_replace("{{{ HOST_REGION }}}",$machine_template['region_name'],$contents);
        $contents = str_replace("{{{ HOST_NAME }}}",$machine_template['name'],$contents);
        return $contents;
    }
}
