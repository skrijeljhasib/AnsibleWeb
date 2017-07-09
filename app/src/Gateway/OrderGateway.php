<?php

namespace Project\Gateway;

use Project\Entity\Order;

class OrderGateway
{

    protected $entityManager;
    protected $defaultTargetEntity = Order::class;

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
     * @param Order|Order[] $orders
     * @return bool
     */
    public function put($orders)
    {
        $orders = !is_array($orders) ? [$orders] : $orders;
        foreach ($orders as $order) {
            $this->getEntityManager()->merge($order);
        }
        $this->getEntityManager()->flush();
        return true;
    }

    /**
     * @param $order
     * @return bool
     */
    public function delete($order)
    {
        $this->getEntityManager()->remove($order);
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
