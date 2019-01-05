<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class DriveTypeRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllDriveTypes()
    {
        return $this->createQueryBuilder('dr')
            ->select('dr.id, dr.type')
            ->getQuery()
            ->getResult();
    }
}