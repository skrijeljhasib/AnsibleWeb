<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 17:03
 */

namespace Project\Entity\JSON;

/**
 * Class MySQLUser
 * @package Project\Entity\JSON
 */
class MySQLUser extends Task
{

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
    private $update_password;

    /**
     * @var string
     */
    private $priv;
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
    public function getUpdatePassword(): string
    {
        return $this->update_password;
    }

    /**
     * @param string $update_password
     */
    public function setUpdatePassword(string $update_password)
    {
        $this->update_password = $update_password;
    }

    /**
     * @return string
     */
    public function getPriv(): string
    {
        return $this->priv;
    }

    /**
     * @param string $priv
     */
    public function setPriv(string $priv)
    {
        $this->priv = $priv;
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
        $array["mysql_user"] = array_filter(get_object_vars($this));

        $array += parent::toArray();

        return $array;
    }
}
