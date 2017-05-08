<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:08
 */

namespace Project\Entity\JSON;


/**
 * Class Task
 * @package Project\Entity\JSON
 */
abstract class Task
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $local_action;
    /**
     * @var
     */
    private $register;
    /**
     * @var
     */
    private $with_items;

    /**
     * @var
     */
    private $until;

    /**
     * @var
     */
    private $retries;

    /**
     * @return mixed
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param mixed $until
     */
    public function setUntil($until)
    {
        $this->until = $until;
    }

    /**
     * @return mixed
     */
    public function getRetries()
    {
        return $this->retries;
    }

    /**
     * @param mixed $retries
     */
    public function setRetries($retries)
    {
        $this->retries = $retries;
    }



    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach (array_filter(get_object_vars($this)) as $key => $value)
        {
            $array[$key] = $value;
        }
        return $array;
    }


    /**
     * @return String
     */
    public function getTName():String
    {
        return $this->name;
    }


    /**
     * @param String $name
     */
    public function setTName(String $name)
    {
        $this->name = $name;
    }


    /**
     * @return String
     */
    public function getRegister():String
    {
        return $this->register;
    }


    /**
     * @param String $register
     */
    public function setRegister(String $register)
    {
        $this->register = $register;
    }


    /**
     * @return array
     */
    public function getWithItems():array
    {
        return $this->with_items;
    }


    /**
     * @param array $with_items
     */
    public function setWithItems(array $with_items)
    {
        $this->with_items = $with_items;
    }


    /**
     * @return String
     */
    public function getLocalAction():String
    {
        return $this->local_action;
    }


    /**
     * @param String $local_action
     */
    public function setLocalAction(String $local_action)
    {
        $this->local_action = $local_action;
    }

}