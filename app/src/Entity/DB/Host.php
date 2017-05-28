<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 25.04.17
 * Time: 16:51
 */

namespace Project\Entity\DB;

/**
 * @Entity @Table(name="hosts")
 * @UniqueEntity(fields="ip", message="Ip already exists.")
 * @UniqueEntity(fields="name", message="Name already exists.")
 **/
class Host
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;

    /**
     * @Column(type="string", length=64, unique=true)
     * @var string
     **/
    protected $name;

    /**
     * @Column(type="string", length=15, unique=true)
     * @var string
     **/
    protected $ip;

    /**
     * @Column(type="string", unique=true)
     * @var string
     **/
    protected $hostid;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $location;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $status;

    /**
     * @Column(type="integer",length=2)
     * @var int
     **/
    protected $state = 0;

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

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
    public function getHostID():string
    {
        return $this->hostid;
    }

    /**
     * @param string $hostID
     */
    public function setHostID(string $hostid)
    {
        $this->hostid = $hostid;
    }

    /**
     * @return string
     */
    public function getLocation():string
    {
        return $this->location;
    }

    /**
     * @param string location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getStatus():string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

   /**
     * @return int
     */
    public function getState():int
    {
        return $this->state;
    }

    /**
     * @param string $status
     */
    public function setState(int $state)
    {
        $this->status = $state;
    }

    /**
     * @return string
     */
    public function getIp():string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }
}
