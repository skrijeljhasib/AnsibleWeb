<?php

namespace Project\Gateway;

use Project\Entity\DB\Host;

class HostGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Host::class;

    /**
     * @param null $id
     *
     * @return array|object
     */
    public function fetch($id = null)
    {
        if ($id) {
            return $this->getRepository()->find($id);
        } else {
            return $this->getRepository()->findAll();
        }
    }

    /**
     * @param string $name
     *
     * @return array|object
     */
    public function fetchByName($name)
    {
        if ($name) {
            return $this->getRepository()->findOneBy(array('name' => $name));
        } else {
            return [];
        }
    }

    /**
     * @param Host|Host[] $hosts
     *
     * @return bool
     */
    public function put($hosts)
    {
        $hosts = !is_array($hosts) ? [$hosts] : $hosts;
        foreach ($hosts as $host) {
            $this->getEntityManager()->merge($host);
        }
        $this->getEntityManager()->flush();
        return true;
    }

     /**
     * @param integer $id
     *
     * @return boolean
     */

    public function delete($id)
    {
        $this->getEntityManager()->remove($id);
        $this->getEntityManager()->flush();
        return true;
    }

     /**
     *
     * @return boolean
     */

    public function deleteAll()
    {
        $this->getEntityManager()->createQuery('DELETE FROM Project\Entity\DB\Host')->execute();
        $this->getEntityManager()->flush();
        return true;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->defaultTargetEntity);
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
