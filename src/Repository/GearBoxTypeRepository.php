<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class GearBoxTypeRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllGearBoxTypes()
    {
        return $this->createQueryBuilder('gb')
            ->select('gb.id, gb.type')
            ->getQuery()
            ->getResult();
    }
}