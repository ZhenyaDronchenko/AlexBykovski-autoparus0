<?php

namespace App\Repository\UserData;

use App\Entity\UserData\ImportAdvertError;
use Doctrine\ORM\EntityRepository;

class ImportAdvertErrorRepository extends EntityRepository
{
    public function deleteByIds($ids)
    {
        return $this->createQueryBuilder('iae')
            ->delete(ImportAdvertError::class, 'iae')
            ->where('iae.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}