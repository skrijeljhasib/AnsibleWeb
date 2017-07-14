<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 25.04.17
 * Time: 16:51
 */

namespace Project\Entity;

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
     * @Column(type="string", length=15, nullable=true)
     * @var string
     **/
    protected $ip = '0.0.0.0';

    /**
     * @Column(type="string", unique=true, nullable=true)
     * @var string
     **/
    protected $hostid = '';

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
     * @Column(type="string")
     * @var string
     **/
    protected $hostgroup = '';

    /**
     * @Column(type="integer",length=2)
     * @var int
     **/
    protected $state = 0;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $inventory = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
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
    public function getHostID(): string
    {
        return $this->hostid;
    }

    /**
     * @param string $hostid
     */
    public function setHostID(string $hostid)
    {
        if ($hostid != '') { 
		$this->hostid = $hostid;
	} else {
		$this->hostid = substr(md5(microtime()), rand(0, 26), 15);
	}
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getStatus(): string
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
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getHostGroup(): string
    {
        return $this->hostgroup;
    }

    /**
     * @param string $hostgroup
     */
    public function setHostGroup(string $hostgroup)
    {
        $this->hostgroup = $hostgroup;
    }

    /**
     * @return string
     */
    public function getIp(): string
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

    /**
     * @return string
     */
    public function getInventory(): string
    {
        return $this->inventory;
    }

    /**
     * @param string $inventory
     */
    public function setInventory(string $inventory)
    {
        $this->inventory = $inventory;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
