<?php

namespace Project\Gateway;

use Project\Entity\DB\Package;

class PackageGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Package::class;

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
     * @param Package|Package[] $packages
     *
     * @return bool
     */
    public function put($packages)
    {
        $packages = !is_array($packages) ? [$packages] : $packages;
        foreach ($packages as $package) {
            $this->getEntityManager()->persist($package);
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
