<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class EngineTypeRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllEngineTypes()
    {
        return $this->createQueryBuilder('en')
            ->select('en.id, en.type')
            ->getQuery()
            ->getResult();
    }
}