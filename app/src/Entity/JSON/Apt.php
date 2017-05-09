<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:15
 */

namespace Project\Entity\JSON;


class Apt extends Task
{
    /**
     *
     */
    const PRESENT = 'present';
    /**
     *
     */
    const LATEST = 'latest';
    /**
     *
     */
    const ABSENT = 'absent';

    /**
     *
     */
    const MULTIPLE_ITEMS = '{{ item }}';



    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $update_cache;
    /**
     * @var string
     */
    private $upgrade;


    /**
     * @param array $with_items
     */
    public function setWithItems(array $with_items)
    {
        parent::setWithItems($with_items);
    }

    /**
     * @return string
     */
    public function getAName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setAName(string $name)
    {
        $this->name = $name;
    }

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
     * @return string
     */
    public function getUpdateCache():string
    {
        return $this->update_cache;
    }

    /**
     * @param string $update_cache
     */
    public function setUpdateCache(string $update_cache)
    {
        $this->update_cache = $update_cache;
    }

    /**
     * @return string
     */
    public function getUpgrade():string
    {
        return $this->upgrade;
    }

    /**
     * @param string $upgrade
     */
    public function setUpgrade(string $upgrade)
    {
        $this->upgrade = $upgrade;
    }

    /**
     * @return array
     */
    public function toArray() :array
    {
        $array["apt"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}