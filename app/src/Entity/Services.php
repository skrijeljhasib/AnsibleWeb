<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 30.05.17
 * Time: 17:16
 */

namespace Project\Entity;

/**
 * @Entity @Table(name="services")
 **/
class Services
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;

    /**
     * @Column(type="string",nullable=false)
     * @var string
     */
    protected $name = '';

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $connect = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $connectadmin = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $chat = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $audit = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $logger = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $mailer = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $filer = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $esm = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $payment = false;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $translate = false;

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
     * @return boolean
     */
    public function getConnect()
    {
        return $this->connect;
    }

    /**
     * @param boolean $installed
     */
    public function setConnect($installed)
    {
        $this->connect = $installed;
    }

    /**
     * @param string $service
     * @param string $name
     */
    public function setService($service)
    {
        switch ($service) {
		case 'esm' : $this->esm = true; break;
		case 'connect' : $this->connect = true; break;
		case 'connect-admin' : $this->connectadmin = true; break;
		case 'chat' : $this->chat = true; break;
		case 'audit' : $this->audit = true; break;
		case 'logger' : $this->logger = true; break;
		case 'filer' : $this->filer = true; break;
		case 'mailer' : $this->mailer = true; break;
		case 'translate' : $this->translate = true; break;
		case 'payment' : $this->payment = true; break;
	}
    }

    public function toArray() {
        return get_object_vars($this);
    }

}
