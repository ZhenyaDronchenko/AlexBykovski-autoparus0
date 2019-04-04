<?php

namespace App\Repository\Error;

use App\Entity\Error\TypeOBD2Error;
use Doctrine\ORM\EntityRepository;

class CodeOBD2ErrorRepository extends EntityRepository
{
    public function findAllUrlsForSiteMap($typeUrl)
    {
        $qb = $this->createQueryBuilder('cobd2')
            ->select('cobd2.url');

        if($typeUrl){
            $qb->join('cobd2.type', "t")
                ->where("t.url = :typeUrl")
                ->setParameter("typeUrl", $typeUrl);
        }

        return $qb->getQuery()
            ->getResult();
    }
}