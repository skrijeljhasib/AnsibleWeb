<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 26.04.17
 * Time: 14:45
 */

namespace Project\Entity;


class PlayBook
{

    protected $name;
    protected $hosts;
    protected $gather_facts;
    protected $connection;
    protected $remote_user;
    protected $become;
    protected $become_method;
    protected $become_user;
    protected $become_flags;
    protected $pre_tasks;
    protected $tasks;
    protected $post_tasks;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return mixed
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * @param mixed $hosts
     */
    public function setHosts($hosts)
    {
        $this->hosts = $hosts;
    }

    /**
     * @return mixed
     */
    public function getGatherFacts()
    {
        return $this->gather_facts;
    }

    /**
     * @param mixed $gather_facts
     */
    public function setGatherFacts($gather_facts)
    {
        $this->gather_facts = $gather_facts;
    }

    /**
     * @return mixed
     */
    public function getRemoteUser()
    {
        return $this->remote_user;
    }

    /**
     * @param mixed $remote_user
     */
    public function setRemoteUser($remote_user)
    {
        $this->remote_user = $remote_user;
    }

    /**
     * @return mixed
     */
    public function getBecome()
    {
        return $this->become;
    }

    /**
     * @param mixed $become
     */
    public function setBecome($become)
    {
        $this->become = $become;
    }

    /**
     * @return mixed
     */
    public function getBecomeMethod()
    {
        return $this->become_method;
    }

    /**
     * @param mixed $become_method
     */
    public function setBecomeMethod($become_method)
    {
        $this->become_method = $become_method;
    }

    /**
     * @return mixed
     */
    public function getBecomeUser()
    {
        return $this->become_user;
    }

    /**
     * @param mixed $become_user
     */
    public function setBecomeUser($become_user)
    {
        $this->become_user = $become_user;
    }

    /**
     * @return mixed
     */
    public function getBecomeFlags()
    {
        return $this->become_flags;
    }

    /**
     * @param mixed $become_flags
     */
    public function setBecomeFlags($become_flags)
    {
        $this->become_flags = $become_flags;
    }

    public function pre_tasks($pre_tasks)
    {
        $this->pre_tasks = $pre_tasks;
    }


    public function tasks($tasks)
    {
        $this->tasks = $tasks;
    }

    public function post_tasks($post_tasks)
    {
        $this->post_tasks = $post_tasks;
    }

    public function shell($shell)
    {
        $arr["shell"] = $shell;

        return $arr;
    }

    public function os_server($name,$state,$auth,$machine_parameters,$register)
    {
        $arr["name"] = $name;
        $arr["os_server"]["state"] = $state;
        $arr["os_server"]["auth"] = $auth;

        foreach ($machine_parameters as $machine_parameter => $machine_value)
        {
            $arr["os_server"][$machine_parameter] = $machine_value;
        }

        $arr["register"] = $register;

        return $arr;

    }

    public function file($name,$state,$path)
    {
        $arr["name"] = $name;
        $arr["file"]["state"] = $state;
        $arr["file"]["path"] = $path;

        return $arr;
    }


    public function lineinfile($name,$path,$line,$other_parameter,$other_value)
    {
        $arr["name"] = $name;
        $arr["lineinfile"]["path"] = $path;
        $arr["lineinfile"][$other_parameter] = $other_value;
        $arr["lineinfile"]["line"] = $line;

        return $arr;
    }

    public function package_manager($name,$package_manager,$items, $state)
    {
        $arr["name"] = $name;
        $arr[$package_manager] = "name={{ item }} state=$state";
        $arr["with_items"] = $items;

        return $arr;
    }

    public function raw($name, $raw)
    {
        $arr["name"] = $name;
        $arr["raw"] = $raw;

        return $arr;
    }

    public function wait_for($name,$host,$port,$state)
    {
        $arr["name"] = $name;
        $arr["local_action"]["wait_for"]["host"] = $host;
        $arr["local_action"]["wait_for"]["port"] = $port;
        $arr["local_action"]["wait_for"]["state"] = $state;

        return $arr;
    }

    public function pause($minutes)
    {
        $arr["pause"]["minutes"] = $minutes;

        return $arr;
    }

    public function toJSON($unset_parameters = null)
    {
        $playbook_array = get_object_vars($this);

        if($unset_parameters !== null)
        {
            foreach ($unset_parameters as $unset_parameter)
            {
                unset($playbook_array[$unset_parameter]);
            }
        }

        return json_encode($playbook_array,JSON_UNESCAPED_SLASHES);
    }

}