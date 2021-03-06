<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class EngineRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllCapacities()
    {
        return $this->createQueryBuilder('en')
            ->select('en.capacity')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllNames()
    {
        return $this->createQueryBuilder('en')
            ->select('en.name')
            ->getQuery()
            ->getResult();
    }
}