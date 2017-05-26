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
 **/
class Hosts
{

    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $name;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $ip;


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
    public function getIp():string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->name = $ip;
    }
}
