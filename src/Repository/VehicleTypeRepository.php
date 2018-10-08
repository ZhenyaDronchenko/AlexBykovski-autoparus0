<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class VehicleTypeRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllVehicleTypes()
    {
        return $this->createQueryBuilder('vt')
            ->select('vt.id, vt.type')
            ->getQuery()
            ->getResult();
    }
}