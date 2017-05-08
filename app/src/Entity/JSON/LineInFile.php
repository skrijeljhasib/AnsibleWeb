<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:23
 */

namespace Project\Entity\JSON;


/**
 * Class LineInFile
 * @package Project\Entity\JSON
 */
class LineInFile extends Task
{
    /**
     * @var
     */
    private $path;

    /**
     * @var
     */
    private $create;

    /**
     * @var
     */
    private $line;


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
     * @return string
     */
    public function getCreate():string
    {
        return $this->create;
    }

    /**
     * @param string $create
     */
    public function setCreate(string $create)
    {
        $this->create = $create;
    }

    /**
     * @return string
     */
    public function getLine():string
    {
        return $this->line;
    }

    /**
     * @param string $line
     */
    public function setLine(string $line)
    {
        $this->line = $line;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        $array["lineinfile"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}