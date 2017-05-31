<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 30.05.17
 * Time: 17:16
 */

namespace Project\Entity\DB;

/**
 * @Entity @Table(name="orders")
 **/
class Order
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;


    /**
     * @Column(type="string", unique=true)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string",nullable=true)
     * @var string
     **/
    protected $packages;

    /**
     * @Column(type="string",nullable=true)
     * @var string
     **/
    protected $db;

    /**
     * @Column(type="string",nullable=true)
     * @var string
     **/
    protected $webserver;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return string|null
     */
    public function getPackages(): string
    {
        return $this->packages;
    }

    /**
     * @param string $packages
     */
    public function setPackages(string $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return string|null
     */
    public function getDatabase(): string
    {
        return $this->db;
    }

    /**
     * @param string $db
     */
    public function setDatabase(string $db)
    {
        $this->db = $db;
    }

    /**
     * @return string|null
     */
    public function getWebserver(): string
    {
        return $this->webserver;
    }

    /**
     * @param string $webserver
     */
    public function setWebserver(string $webserver)
    {
        $this->webserver = $webserver;
    }

}