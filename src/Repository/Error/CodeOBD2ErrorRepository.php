<?php

namespace App\Repository\Error;

use App\Entity\Error\TypeOBD2Error;
use Doctrine\ORM\EntityRepository;

class CodeOBD2ErrorRepository extends EntityRepository
{
    public function findAllUrlsForSiteMap(TypeOBD2Error $type = null)
    {
        $qb = $this->createQueryBuilder('cobd2')
            ->select('cobd2.url');

        if($type instanceof TypeOBD2Error){
            $qb->where("cobd2.type = :type")
                ->setParameter("type", $type);
        }

        return $qb->getQuery()
            ->getResult();
    }
}