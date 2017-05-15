<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 12.05.17
 * Time: 14:22
 */

namespace Project\Entity\JSON;


class MySQLDB extends Task
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
     * @var string
     */
    private $login_user;


    /**
     * @var string
     */
    private $login_password;

    /**
     * @return string
     */
    public function getDName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setDName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLoginUser(): string
    {
        return $this->login_user;
    }

    /**
     * @param string $login_user
     */
    public function setLoginUser(string $login_user)
    {
        $this->login_user = $login_user;
    }

    /**
     * @return string
     */
    public function getLoginPassword(): string
    {
        return $this->login_password;
    }

    /**
     * @param string $login_password
     */
    public function setLoginPassword(string $login_password)
    {
        $this->login_password = $login_password;
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
        $array["mysql_db"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }

}