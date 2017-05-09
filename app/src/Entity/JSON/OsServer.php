<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:40
 */

namespace Project\Entity\JSON;


/**
 * Class OsServer
 * @package Project\Entity\JSON
 */
class OsServer extends Task
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var array
     */
    private $auth;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key_name;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $flavor;

    /**
     * @var string
     */
    private $timeout;

    /**
     * @var string
     */
    private $auto_ip;

    /**
     * @var string
     */
    private $network;

    /**
     * @var string
     */
    private $region_name;


    /**
     * @return string
     */
    public function getState():string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

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
    public function getOSName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setOSName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKeyName():string
    {
        return $this->key_name;
    }

    /**
     * @param string $key_name
     */
    public function setKeyName(string $key_name)
    {
        $this->key_name = $key_name;
    }

    /**
     * @return string
     */
    public function getImage():string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getFlavor():string
    {
        return $this->flavor;
    }

    /**
     * @param string $flavor
     */
    public function setFlavor(string $flavor)
    {
        $this->flavor = $flavor;
    }

    /**
     * @return string
     */
    public function getTimeout():string
    {
        return $this->timeout;
    }

    /**
     * @param string $timeout
     */
    public function setTimeout(string $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return string
     */
    public function getAutoIp():string
    {
        return $this->auto_ip;
    }


    /**
     * @param string $auto_ip
     */
    public function setAutoIp(string $auto_ip)
    {
        $this->auto_ip = $auto_ip;
    }

    /**
     * @return string
     */
    public function getNetwork():string
    {
        return $this->network;
    }


    /**
     * @param string $network
     */
    public function setNetwork(string $network)
    {
        $this->network = $network;
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
     * @param array $machine_template
     */
    public function setMachineFromConfigFile(array $machine_template)
    {
        foreach ($machine_template as $key => $value)
        {
            $this->$key = $value;
        }
    }


    /**
     * @return array
     */
    public function toArray():array
    {
        $array["os_server"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}