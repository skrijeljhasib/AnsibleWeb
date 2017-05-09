<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 08.05.17
 * Time: 11:28
 */

namespace Project\Entity\JSON;

/**
 * Class OsServerAuth
 * @package Project\Entity\JSON
 */
class OsServerAuth extends Task
{

    /**
     * @var string
     */
    private $auth_url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $project_name;


    /**
     * @return string
     */
    public function getAuthUrl():string
    {
        return $this->auth_url;
    }


    /**
     * @param string $auth_url
     */
    public function setAuthUrl(string $auth_url)
    {
        $this->auth_url = $auth_url;
    }


    /**
     * @return string
     */
    public function getUsername():string
    {
        return $this->username;
    }


    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }


    /**
     * @return string
     */
    public function getPassword():string
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
    public function getProjectName():string
    {
        return $this->project_name;
    }

    /**
     * @param string $project_name
     */
    public function setProjectName(string $project_name)
    {
        $this->project_name = $project_name;
    }

    /**
     * @param array $openstack_auth
     */
    public function setAuthFromConfigFile(array $openstack_auth)
    {
        foreach ($openstack_auth as $key => $value)
        {
            $this->$key = $value;
        }
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