<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:23
 */

namespace Project\Entity\JSON;


/**
 * Class File
 * @package Project\Entity\JSON
 */
class File extends Task
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $src;

    /**
     * @var string
     */
    private $path;

    /**
     * @return string
     */
    public function getState():string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc(string $src)
    {
        $this->src = $src;
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
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        $array["file"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}