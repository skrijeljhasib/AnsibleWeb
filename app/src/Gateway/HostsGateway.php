<?php

namespace Project\Gateway;

use Project\Entity\DB\Hosts;

class HostsGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Hosts::class;

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
     * @param Hosts|Hosts[] $hosts
     *
     * @return bool
     */
    public function put($hosts)
    {
        $hosts = !is_array($hosts) ? [$hosts] : $hosts;
        foreach ($hosts as $host) {
            $this->getEntityManager()->persist($host);
        }
        $this->getEntityManager()->flush();
        return true;
    }


    public function delete($id)
    {
        $this->getEntityManager()->delete($id);
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
