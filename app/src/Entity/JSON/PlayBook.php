<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 26.04.17
 * Time: 14:45
 */

namespace Project\Entity\JSON;


/**
 * Class PlayBook
 * @package Project\Entity
 */
class PlayBook
{

    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $hosts;
    /**
     * @var string
     */
    protected $gather_facts;
    /**
     * @var string
     */
    protected $connection;
    /**
     * @var string
     */
    protected $remote_user;
    /**
     * @var string
     */
    protected $become;
    /**
     * @var string
     */
    protected $become_method;
    /**
     * @var string
     */
    protected $become_user;
    /**
     * @var string
     */
    protected $become_flags;
    /**
     * @var array
     */
    protected $pre_tasks;
    /**
     * @var array
     */
    protected $tasks;
    /**
     * @var array
     */
    protected $post_tasks;


    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getConnection():string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     */
    public function setConnection(string $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return string
     */
    public function getHosts():string
    {
        return $this->hosts;
    }


    /**
     * @param string $hosts
     */
    public function setHosts(string $hosts)
    {
        $this->hosts = $hosts;
    }

    /**
     * @return string
     */
    public function getGatherFacts():string
    {
        return $this->gather_facts;
    }


    /**
     * @param string $gather_facts
     */
    public function setGatherFacts(string $gather_facts)
    {
        $this->gather_facts = $gather_facts;
    }

    /**
     * @return string
     */
    public function getRemoteUser():string
    {
        return $this->remote_user;
    }

    /**
     * @param string $remote_user
     */
    public function setRemoteUser(string $remote_user)
    {
        $this->remote_user = $remote_user;
    }


    /**
     * @return string
     */
    public function getBecome():string
    {
        return $this->become;
    }


    /**
     * @param string $become
     */
    public function setBecome(string $become)
    {
        $this->become = $become;
    }


    /**
     * @return string
     */
    public function getBecomeMethod():string
    {
        return $this->become_method;
    }


    /**
     * @param string $become_method
     */
    public function setBecomeMethod(string $become_method)
    {
        $this->become_method = $become_method;
    }


    /**
     * @return string
     */
    public function getBecomeUser():string
    {
        return $this->become_user;
    }


    /**
     * @param string $become_user
     */
    public function setBecomeUser(string $become_user)
    {
        $this->become_user = $become_user;
    }


    /**
     * @return string
     */
    public function getBecomeFlags():string
    {
        return $this->become_flags;
    }

    /**
     * @param string $become_flags
     */
    public function setBecomeFlags(string $become_flags)
    {
        $this->become_flags = $become_flags;
    }


    /**
     * @param array $pre_tasks
     */
    public function setPreTask(array $pre_tasks)
    {
        $this->pre_tasks[] = $pre_tasks;
    }


    /**
     * @param array $tasks
     */
    public function setTask(array $tasks)
    {
        $this->tasks[] = $tasks;
    }


    /**
     * @param array $post_tasks
     */
    public function setPostTask(array $post_tasks)
    {
        $this->post_tasks[] = $post_tasks;
    }


    /**
     * @return array
     */
    public function getPreTasks():array
    {
        return $this->pre_tasks;
    }


    /**
     * @return array
     */
    public function getTasks():array
    {
        return $this->tasks;
    }


    /**
     * @return array
     */
    public function getPostTasks():array
    {
        return $this->post_tasks;
    }


    /**
     * @return string
     */
    public function toJSON():string
    {
        $playbook_array = array_filter(get_object_vars($this));

        return json_encode($playbook_array,JSON_UNESCAPED_SLASHES);
    }

}