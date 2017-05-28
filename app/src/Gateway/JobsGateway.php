<?php

namespace Project\Gateway;

use Project\Entity\DB\Jobs;

class JobsGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Jobs::class;

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
     * @param Jobs|Jobs[] $jobs
     *
     * @return bool
     */
    public function put($jobs)
    {
        $jobs = !is_array($jobs) ? [$jobs] : $jobs;
        foreach ($jobs as $job) {
            $this->getEntityManager()->merge($job);
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
