<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:15
 */

namespace Project\Entity\JSON;


/**
 * Class Raw
 * @package Project\Entity\JSON
 */
class Raw extends Task
{
    /**
     * @var
     */
    private $raw;

    /**
     * @return string
     */
    public function getRaw():string
    {
        return $this->raw;
    }

    /**
     * @param string $raw
     */
    public function setRaw(string $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        $array = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}