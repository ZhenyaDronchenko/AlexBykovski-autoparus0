<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PhoneSparePartRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByWorkText($text)
    {
        return $this->createQueryBuilder('phspp')
            ->select('phspp')
            ->where("phspp.work LIKE :text")
            ->andWhere("phspp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%')
            ->orderBy("phspp.work", "ASC")
            ->getQuery()
            ->getResult();
    }
}