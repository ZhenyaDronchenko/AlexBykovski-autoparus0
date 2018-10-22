<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PhoneBrandRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByText($text)
    {
        return $this->createQueryBuilder('phbr')
            ->select('phbr')
            ->where("phbr.name LIKE :text")
            ->andWhere("phbr.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%')
            ->orderBy("phbr.name", "ASC")
            ->getQuery()
            ->getResult();
    }
}