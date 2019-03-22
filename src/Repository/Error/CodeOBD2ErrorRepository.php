<?php

namespace App\Repository\Error;

use Doctrine\ORM\EntityRepository;

class CodeOBD2ErrorRepository extends EntityRepository
{
    public function findAllUrlsForSiteMap()
    {
        return $this->createQueryBuilder('cobd2')
            ->select('cobd2.url')
            ->getQuery()
            ->getResult();
    }
}