<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 02.06.17
 * Time: 14:10
 */

namespace Project\Entity\JSON;


class OvhDns extends Task
{

    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $domain;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getHName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setHName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        $array["ovh_dns"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}