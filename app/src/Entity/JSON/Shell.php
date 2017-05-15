<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:24
 */

namespace Project\Entity\JSON;


/**
 * Class Shell
 * @package Project\Entity\JSON
 */
class Shell extends Task
{
    /**
     * @var string
     */
    private $shell;

    /**
     * @return string
     */
    public function getShell():string
    {
        return $this->shell;
    }

    /**
     * @param string $shell
     */
    public function setShell(string $shell)
    {
        $this->shell = $shell;
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