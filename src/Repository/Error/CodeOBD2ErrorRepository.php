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

    /**
     * @param string $text
     * @param TypeOBD2Error $type
     *
     * @return array
     */
    public function searchByText($text, TypeOBD2Error $type = null)
    {
        $query = $this->createQueryBuilder('cobd2')
            ->select('cobd2')
            ->where( "cobd2.code LIKE :text")
            ->andWhere("cobd2.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%');

        if($type){
            $query = $query
                ->andWhere("cobd2.type = :type")
                ->setParameter("type", $type);
        }

        return $query->orderBy("cobd2.code", "ASC")
            ->getQuery()
            ->getResult();
    }
}