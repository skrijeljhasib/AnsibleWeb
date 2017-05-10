<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 10.05.17
 * Time: 15:24
 */

namespace Project\Entity\JSON;

/**
 * Class OsServerFacts
 * @package Project\Entity\JSON
 */
class OsServerFacts extends Task
{
    /**
     * @var array
     */
    private $auth;

    /**
     * @var string
     */
    private $region_name;

    /**
     * @return array
     */
    public function getAuth():array
    {
        return $this->auth;
    }

    /**
     * @param array $auth
     */
    public function setAuth(array $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return string
     */
    public function getRegionName():string
    {
        return $this->region_name;
    }

    /**
     * @param string $region_name
     */
    public function setRegionName(string $region_name)
    {
        $this->region_name = $region_name;
    }


    /**
     * @return array
     */
    public function toArray():array
    {
        $array["os_server_facts"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}