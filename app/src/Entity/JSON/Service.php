<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 10:41
 */

namespace Project\Entity\JSON;


class Service extends Task
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $state;

    /**
     * @return string
     */
    public function getSName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setSName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getState(): string
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
    public function toArray():array
    {
        $array["service"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }


}