<?php

namespace Project\Gateway;

use Project\Entity\Services;

class ServicesGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Services::class;

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
     * @param string $name
     * @param string $service
     *
     * @return array|object
     */
    public function fetchByNameAndService($name,$service)
    {
        if ($name && $service) {
            return $this->getRepository()->findOneBy(array('name' => $name, $service => true));
        } else {
            return [];
        }
    }

    /**
     * @param Services|Services[] $services
     * @return bool
     */
    public function put($services)
    {
        $services = !is_array($services) ? [$services] : $services;
        foreach ($services as $service) {
            $this->getEntityManager()->merge($service);
        }
        $this->getEntityManager()->flush();
        return true;
    }

    /**
     * @param $service
     * @return bool
     */
    public function delete($service)
    {
        $this->getEntityManager()->remove($service);
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
