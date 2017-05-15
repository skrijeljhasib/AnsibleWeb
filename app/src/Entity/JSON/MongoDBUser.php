<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 12.05.17
 * Time: 14:25
 */

namespace Project\Entity\JSON;


class MongoDBUser extends Task
{

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $state;

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getUName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setUName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
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
        $array["mongodb_user"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }

}