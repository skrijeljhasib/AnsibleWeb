<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 25.04.17
 * Time: 16:51
 */

namespace Project\Entity\DB;

/**
 * @Entity @Table(name="jobs")
 **/
class Jobs
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;

    /**
     * @Column(type="string", length=64)
     * @var string
     **/
    protected $name;
    /**
     * @Column(type="json_array")
     * @var string
     **/
    protected $json;

    /**
     * @Column(type="string", length=64)
     * @var string
     **/
    protected $tube;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $status;

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
    public function getJson():string
    {
        return $this->json;
    }

    /**
     * @param string $json
     */
    public function setJson(string $json)
    {
        $this->json = $json;
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
     * @return string
     */
    public function getTube():string
    {
        return $this->tube;
    }

    /**
     * @param string $status
     */
    public function setTube(string $tube)
    {
        $this->tube = $tube;
    }

}
