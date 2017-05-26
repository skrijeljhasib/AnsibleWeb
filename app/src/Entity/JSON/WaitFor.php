<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:24
 */

namespace Project\Entity\JSON;

/**
 * Class WaitFor
 * @package Project\Entity\JSON
 */
class WaitFor extends Task
{

    /**
     * @var string
     */
    private $host;


    /**
     * @var string
     */
    private $port;


    /**
     * @var string
     */
    private $delay;


    /**
     * @return String
     */
    public function getHost():String
    {
        return $this->host;
    }


    /**
     * @param String $host
     */
    public function setHost(String $host)
    {
        $this->host = $host;
    }


    /**
     * @return String
     */
    public function getPort():String
    {
        return $this->port;
    }


    /**
     * @param String $port
     */
    public function setPort(String $port)
    {
        $this->port = $port;
    }


    /**
     * @return String
     */
    public function getDelay():String
    {
        return $this->delay;
    }


    /**
     * @param String $delay
     */
    public function setDelay(String $delay)
    {
        $this->delay = $delay;
    }


    /**
     * @return array
     */
    public function toArray():array
    {
        $array["wait_for"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}
